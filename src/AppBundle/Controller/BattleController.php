<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\User;
use AppBundle\Entity\UserReport;
use AppBundle\Form\UnitTravelCountsType;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\ArmyMovement\JourneyServiceInterface;
use AppBundle\Service\ArmyMovement\StartJourneyServiceInterface;
use AppBundle\Service\Battle\BattleReportServiceInterface;
use AppBundle\Service\Platform\PlatformDataServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("settlement/{id}", requirements={"id" = "\d+"})
 * Class BattleController
 * @package AppBundle\Controller
 */
class BattleController extends MainController
{
    /**
     * @var JourneyServiceInterface
     */
    private $armyJourneyService;

    public function __construct(GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService,
                                JourneyServiceInterface $journeyService)
    {
        parent::__construct($gameStateService, $platformService);

        $this->armyJourneyService = $journeyService;
    }


    /**
     * @Route("/attack/player/{playerId}",
     *     name="send_attack",
     *     requirements={"playerId" = "\d+"},
     *     methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendAttackFormAction(int $id,
                                         int $playerId,
                                         UserServiceInterface $userService)
    {
        $currentUser = $this->getUser();
        $target = $userService->getById($playerId);

        if ($currentUser === $target) {
            $this->addFlash('error', 'Cannot attack yourself!');

            return $this->redirectToRoute('players_all', ['id' => $id]);
        }

        $form = $this->getUnitsForm($currentUser);

        return $this->render('battle/send-attack-new.html.twig', [
            'defender' => $target,
            'units_form' => $form->createView()
        ]);
    }


    /**
     * @Route("/attack/player/{playerId}",
     *     name="send_attack_process",
     *     requirements={"playerId" = "\d+"},
     *     methods={"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendAttackProcessAction(int $id,
                                            int $playerId,
                                            Request $request,
                                            UserServiceInterface $userService,
                                            StartJourneyServiceInterface $startJourneyService)
    {
        $currentUser = $this->getUser();
        $target = $userService->getById($playerId);

        $form = $this->getUnitsForm($currentUser);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $this->addFlash('error', 'Invalid troops count.');

            return $this->render('battle/send-attack-new.html.twig', [
                'defender' => $target,
                'units_form' => $form->createView()
            ]);
        }

        try {
            $startJourneyService->startJourney($form->getData(), $currentUser, $target);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Invalid input. Attack was not sent.');
        }

        return $this->redirectToRoute('send_attack', ['id' => $id, 'playerId' => $playerId]);
    }

    /**
     * @Route("/attacks/all", name="show_attacks")
     */
    public function showAllAttacks(PlatformDataServiceInterface $platformDataService)
    {
        $platform = $platformDataService->getCurrentPlatform();
        /** @var ArmyJourney[] $ownAttacks */
        $ownAttacks = $this->armyJourneyService->getAllOwnJourneys($platform->getGridCell());

        /** @var ArmyJourney[] $ownAttacks */
        $enemyAttacks = $this->armyJourneyService->getAllEnemyJourneys($platform->getGridCell());

        return $this->render('battle/show-all-attacks.html.twig', [
            'ownAttacks' => $ownAttacks,
            'enemyAttacks' => $enemyAttacks,
            'currentPage' =>'attacks'
        ]);
    }

    /**
     * @Route("/reports/all", name="show_reports")
     */
    public function showUserReports(BattleReportServiceInterface $battleReportService)
    {
        /** @var UserReport[] $reports */
        $reports = $battleReportService->getAllByUser($this->getUser()->getId());
        return $this->render('battle/reports-all.html.twig', [
            'reports' => $reports,
            'currentPage' => 'reports'
        ]);
    }

    /**
     * @Route("/report/{reportId}", name="show_report", requirements={"reportId": "\d+"})
     */
    public function showOneReport(int $reportId,
                                  BattleReportServiceInterface $battleReportService)
    {
        $userReport = $battleReportService->getUserReport($this->getUser()->getId(), $reportId);
        dump($userReport);
        return $this->render('battle/report-show.html.twig', [
            'userReport' => $userReport
        ]);
    }

    /**
     * @Route("/report/{reportId}/delete", name="delete_report", requirements={"reportId": "\d+"})
     */
    public function deleteReport(int $id,
                                 int $reportId,
                                  BattleReportServiceInterface $battleReportService)
    {
        $battleReportService->deleteUserReport($this->getUser()->getId(), $reportId);

        return $this->redirectToRoute('show_reports', ['id' => $id]);
    }

    /**
     * @param $currentUser
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUnitsForm(User $currentUser): \Symfony\Component\Form\FormInterface
    {
        $form = $this->createForm(
            UnitTravelCountsType::class,
            [],
            ['units' => $currentUser->getCurrentPlatform()->getUnits()]
        );

        return $form;
    }
}
