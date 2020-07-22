<?php 
use App\SosRequest;
use App\Sos;

return [
    'sos_request' => [
        'status' => [
            SosRequest::STATUS_PENDING => 'Pending',
            SosRequest::STATUS_IN_PROGRESS => 'In Progress',
            SosRequest::STATUS_COMPLETED => 'Completed',
        ],
    ],
    'sos' => [
        'type' => [
            Sos::TYPE_GROCERY => 'Grocery',
            Sos::TYPE_SKILL_SHARE => 'Skill Share',
            Sos::TYPE_CHORE => 'Chore',
        ],
    ] 
];

?>