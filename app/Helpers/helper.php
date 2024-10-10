<?php

namespace App\Helpers;

use App\Models\ShopReduction;

use App\Models\LiaisonShopArticlesShopReductions;
use App\Models\LiaisonUserShopReduction;
use App\Models\UserReductionUsage;
use Illuminate\Support\Facades\Auth;

function getReducedPrice($articleId, $originalPrice, $user_id) {
    $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();

    $reducedPrice = $originalPrice;
    // Check if user is authenticated
    if (Auth::check()) {
        $userId = $user_id;
        $shopReductions = $shopReductions->filter(function ($shopReduction) use ($userId) {
            // Keep reductions linked to authenticated user or not linked to any user
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhereNull('user_id');
                })
                ->first();
            // Keep reductions not linked to any user
            $noUserLiaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first() === null;
            return $liaison !== null || $noUserLiaison;
        });
    } else {
        // Filter out reductions linked to any user
        $shopReductions = $shopReductions->filter(function ($shopReduction) {
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first();
            return !$liaison;
        });
    }

    $valueReductions = [];
    $percentageReductions = [];

    // Collect value and percentage reductions
    // Collect value and percentage reductions
foreach ($shopReductions as $shopReduction) {
    $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
        ->whereNull('destroy')
        ->where('state', 1)
        ->whereDate('startvalidity', '<=', now())
        ->whereDate('endvalidity', '>=', now())
        ->first();

    if ($reduction) {
        // Check if the reduction is usable more than 0 times
        if ($reduction->usable != 0) {
            // Get the number of times the user has used this reduction
            $userReductionUsage = UserReductionUsage::where('user_id', $userId)
                ->where('reduction_id', $reduction->id_shop_reduction)
                ->first();

            $usageCount = $userReductionUsage ? $userReductionUsage->usage_count : 0;

            // If the user has used this reduction less times than it's usable, apply the reduction
            if ($usageCount < $reduction->usable) {
                if ($reduction->value != 0) {
                    $valueReductions[] = $reduction->value;
                } elseif ($reduction->percentage != 0) {
                    $percentageReductions[] = $reduction->percentage;
                }

                // Increase the usage count for this reduction
                if ($userReductionUsage) {
                    $userReductionUsage->usage_count++;
                    $userReductionUsage->save();
                } else {
                    UserReductionUsage::create([
                        'user_id' => $userId,
                        'reduction_id' => $reduction->id_shop_reduction ,
                        'usage_count' => 1
                    ]);
                }
            }
        } else {
            // If the reduction is usable unlimited times, apply the reduction
            if ($reduction->value != 0) {
                $valueReductions[] = $reduction->value;
            } elseif ($reduction->percentage != 0) {
                $percentageReductions[] = $reduction->percentage;
            }
        }
    }
}


    // Apply value reductions
    foreach ($valueReductions as $valueReduction) {
            $reducedPrice -= $valueReduction;
    }

    // Apply largest percentage reduction
    if (!empty($percentageReductions)) {
        $maxPercentageReduction = max($percentageReductions);
        $reducedPrice *= (1 - ($maxPercentageReduction / 100));
    }
    return $reducedPrice;
    
}

function getFirstReductionDescription($articleId, $user_id) {
    $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();
    $reductionDescription = '';

    // Check if user is authenticated
    if (Auth::check()) {
        $userId = $user_id;
        $shopReductions = $shopReductions->filter(function ($shopReduction) use ($userId) {
            // Keep reductions linked to authenticated user or not linked to any user
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhereNull('user_id');
                })
                ->first();
            // Keep reductions not linked to any user
            $noUserLiaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first() === null;
            return $liaison !== null || $noUserLiaison;
        });
    } else {
        // Filter out reductions linked to any user
        $shopReductions = $shopReductions->filter(function ($shopReduction) {
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first();
            return !$liaison;
        });
    }

    // Get description of first reduction
    foreach ($shopReductions as $shopReduction) {
        $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
            ->whereNull('destroy')
            ->where('state', 1)
            ->whereDate('startvalidity', '<=', now())
            ->whereDate('endvalidity', '>=', now())
            ->first();

        if ($reduction) {
            $reductionDescription = $reduction->description;
            break;
        }
    }
    
    return $reductionDescription;
}

function getFirstReductionDescriptionGuest($articleId) {
    $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();
    $reductionDescription = '';

    // Check if user is authenticated
    if (Auth::check()) {
        $userId = auth()->user()->user_id;
        $shopReductions = $shopReductions->filter(function ($shopReduction) use ($userId) {
            // Keep reductions linked to authenticated user or not linked to any user
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhereNull('user_id');
                })
                ->first();
            // Keep reductions not linked to any user
            $noUserLiaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first() === null;
            return $liaison !== null || $noUserLiaison;
        });
    } else {
        // Filter out reductions linked to any user
        $shopReductions = $shopReductions->filter(function ($shopReduction) {
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first();
            return !$liaison;
        });
    }
    // Get description of first reduction
    foreach ($shopReductions as $shopReduction) {
        $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
            ->whereNull('destroy')
            ->where('state', 1)
            ->whereDate('startvalidity', '<=', now())
            ->whereDate('endvalidity', '>=', now())
            ->first();

        if ($reduction) {
            $reductionDescription = $reduction->description;
            break;
        }
    }
    
    return $reductionDescription;
}

function getReducedPriceGuest($articleId, $originalPrice) {
    $shopReductions = LiaisonShopArticlesShopReductions::where('id_shop_article', $articleId)->get();
    $reducedPrice = $originalPrice;
    // Check if user is authenticated
    if (Auth::check()) {
        $userId = Auth::user()->user_id;
        $shopReductions = $shopReductions->filter(function ($shopReduction) use ($userId) {
            // Keep reductions linked to authenticated user or not linked to any user
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhereNull('user_id');
                })
                ->first();
            // Keep reductions not linked to any user
            $noUserLiaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first() === null;
            return $liaison !== null || $noUserLiaison;
        });
    } else {
        // Filter out reductions linked to any user
        $shopReductions = $shopReductions->filter(function ($shopReduction) {
            $liaison = LiaisonUserShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)->first();
            return !$liaison;
        });
    }

    $valueReductions = [];
    $percentageReductions = [];

    // Collect value and percentage reductions
    foreach ($shopReductions as $shopReduction) {
        $reduction = ShopReduction::where('id_shop_reduction', $shopReduction->id_shop_reduction)
            ->whereNull('destroy')
            ->where('state', 1)
            ->whereDate('startvalidity', '<=', now())
            ->whereDate('endvalidity', '>=', now())
            ->first();

        if ($reduction) {
            if ($reduction->value != 0) {
                $valueReductions[] = $reduction->value;
            } elseif ($reduction->percentage != 0) {
                $percentageReductions[] = $reduction->percentage;
            }
        }
    }

    // Apply value reductions
    foreach ($valueReductions as $valueReduction) {
            $reducedPrice -= $valueReduction;
    }

    // Apply largest percentage reduction
    if (!empty($percentageReductions)) {
        $maxPercentageReduction = max($percentageReductions);
        $reducedPrice *= (1 - ($maxPercentageReduction / 100));
    }
    return $reducedPrice;
    
}