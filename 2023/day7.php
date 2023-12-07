<?php
include 'common.php';

$lines = get_input(7, false);
$hands = [];
$bids = [];

function card_value($card) {
    $value = match($card) {
        'A' => 14,
        'K' => 13,
        'Q' => 12,
        'J' => 1,
        'T' => 10,
        '9' => 9,
        '8' => 8,
        '7' => 7,
        '6' => 6,
        '5' => 5,
        '4' => 4,
        '3' => 3,
        '2' => 2,
    };

    return $value;
}

function sort_hand($hand) {
    $hand_arr = str_split($hand);
    usort( $hand_arr, function($a, $b) {
        $va = card_value($a);
        $vb = card_value($b);

        return $va > $vb;
    });
    return implode('', $hand_arr);
}

function get_type($hand) {
    $hand = sort_hand($hand);
    // check 5oak
    if ($hand[0] === $hand[1] && $hand[1] === $hand[2] && $hand[2] === $hand[3] && $hand[3] === $hand[4]) {
        return 7; // five of a kind
    }
    if ($hand[0] === $hand[1] && $hand[1] === $hand[2] && $hand[2] === $hand[3] ||
        $hand[1] === $hand[2] && $hand[2] === $hand[3] && $hand[3] === $hand[4]) {
        return 6; // four of a kind
    }
    if ($hand[0] === $hand[1] && $hand[1] === $hand[2] && $hand[3] === $hand[4] ||
        $hand[0] === $hand[1] && $hand[2] === $hand[3] && $hand[3] === $hand[4]) {
        return 5; // full house
    }
    if ($hand[0] === $hand[1] && $hand[1] === $hand[2] ||
        $hand[1] === $hand[2] && $hand[2] === $hand[3] ||
        $hand[2] === $hand[3] && $hand[3] === $hand[4]) {
        return 4; // 3 of a kind
    }
    if ($hand[0] === $hand[1] && $hand[2] === $hand[3] ||
        $hand[0] === $hand[1] && $hand[3] === $hand[4] ||
        $hand[1] === $hand[2] && $hand[3] === $hand[4]) {
        return 3; // two pair
    }
    if ($hand[0] === $hand[1] || $hand[1] === $hand[2] || $hand[2] === $hand[3] || $hand[3] === $hand[4]) {
        return 2; // one pair
    }
    return 1; // high card
}

function get_wild_type($hand) {
    if (str_contains($hand, 'J')) {
        $highest = 0;
        foreach ( ['A', 'K', 'Q', 'T', '9', '8', '7', '6', '5', '4', '3', '2'] as $wild) {
            $new_hand = str_replace('J', $wild, $hand);
            $type = get_type($new_hand);
            if ($type > $highest) {
                $highest = $type;
            }
        }
        return $highest;
    } else {
        return get_type($hand);
    }
}

foreach ($lines as $line) {
    [$hand, $bid] = explode(' ', $line);
    $hands[] = [
        'hand' => $hand,
        'bid' => $bid,
        'place' => 0,
        'type' => get_wild_type($hand),
    ];
}

usort( $hands, function ($a, $b) {
    if ($a['type'] !== $b['type']) {
        return $a['type'] > $b['type'];
    }
    for($i = 0; $i < 5; $i++) {
        if ($a['hand'][$i] !== $b['hand'][$i]) {
            return card_value($a['hand'][$i]) > card_value($b['hand'][$i]);
        }
    }
});

$winnings = 0;
foreach ($hands as $k => $hand) {
    $winnings += $hand['bid'] * ($k+1);
}
d($winnings);

