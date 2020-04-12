<?php 
use App\Sos;

return [
    'sos' => [
        'status' => [
            Sos::STATUS_PENDING => 'Pending',
            Sos::STATUS_IN_PROGRESS => 'In Progress',
            Sos::STATUS_COMPLETED => 'Completed',
        ],
        'delivery_option' => [
            Sos::DELIVARY_OPTION_DOORFRONT => 'Door Front',
            Sos::DELIVARY_OPTION_INTERCOM => 'Intercom',
            Sos::DELIVARY_OPTION_OTHERS => 'Others',
        ],
        'payment_option' => [
            Sos::PAYMENT_OPTION_CASH => 'Cash',
            Sos::PAYMENT_OPTION_NOT_APPLICABLE => 'Not Applicable',
            Sos::PAYMENT_OPTION_OTHERS => 'Others',
            Sos::PAYMENT_OPTION_PAID_ONLINE => 'Paid Online',
        ],
    ] 
];

?>