<?php

namespace App\Enums;

enum SubscriptionEnums
{
    //
    const dailyMode = "day";
    const weeklyMode = "week";
    const monthlyMode = "month";
    const yearlyMode = "year";

    const liveMode = "live";
    const trialMode = "trial";

    const planPurchaseCode = "plan-purchase";
    const planRenewalCode = "plan-renewal";
    const planDowngradeCode = "plan-downgrade";

    const narrations = [
        self::planPurchaseCode => "Purchased a subscription plan",
        self::planRenewalCode => "Renewed a subscription plan"
    ];
}
