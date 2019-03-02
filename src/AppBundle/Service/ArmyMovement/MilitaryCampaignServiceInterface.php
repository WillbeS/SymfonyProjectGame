<?php

namespace AppBundle\Service\ArmyMovement;


use AppBundle\Entity\MilitaryCampaign;
use AppBundle\Entity\Platform;

interface MilitaryCampaignServiceInterface
{
    public function startCampaign(array $requestData,
                                  Platform $origin,
                                  Platform $destination);

    public function processCampaign(MilitaryCampaign $scheduledTask);

    public function processReturnCampaign(MilitaryCampaign $campaign);
}