<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Point3;
use App\Event\Year2021\Helpers\Scanner;

class Day19 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '79' => <<<'INPUT'
            --- scanner 0 ---
            404,-588,-901
            528,-643,409
            -838,591,734
            390,-675,-793
            -537,-823,-458
            -485,-357,347
            -345,-311,381
            -661,-816,-575
            -876,649,763
            -618,-824,-621
            553,345,-567
            474,580,667
            -447,-329,318
            -584,868,-557
            544,-627,-890
            564,392,-477
            455,729,728
            -892,524,684
            -689,845,-530
            423,-701,434
            7,-33,-71
            630,319,-379
            443,580,662
            -789,900,-551
            459,-707,401
            
            --- scanner 1 ---
            686,422,578
            605,423,415
            515,917,-361
            -336,658,858
            95,138,22
            -476,619,847
            -340,-569,-846
            567,-361,727
            -460,603,-452
            669,-402,600
            729,430,532
            -500,-761,534
            -322,571,750
            -466,-666,-811
            -429,-592,574
            -355,545,-477
            703,-491,-529
            -328,-685,520
            413,935,-424
            -391,539,-444
            586,-435,557
            -364,-763,-893
            807,-499,-711
            755,-354,-619
            553,889,-390
            
            --- scanner 2 ---
            649,640,665
            682,-795,504
            -784,533,-524
            -644,584,-595
            -588,-843,648
            -30,6,44
            -674,560,763
            500,723,-460
            609,671,-379
            -555,-800,653
            -675,-892,-343
            697,-426,-610
            578,704,681
            493,664,-388
            -671,-858,530
            -667,343,800
            571,-461,-707
            -138,-166,112
            -889,563,-600
            646,-828,498
            640,759,510
            -630,509,768
            -681,-892,-333
            673,-379,-804
            -742,-814,-386
            577,-820,562
            
            --- scanner 3 ---
            -589,542,597
            605,-692,669
            -500,565,-823
            -660,373,557
            -458,-679,-417
            -488,449,543
            -626,468,-788
            338,-750,-386
            528,-832,-391
            562,-778,733
            -938,-730,414
            543,643,-506
            -524,371,-870
            407,773,750
            -104,29,83
            378,-903,-323
            -778,-728,485
            426,699,580
            -438,-605,-362
            -469,-447,-387
            509,732,623
            647,635,-688
            -868,-804,481
            614,-800,639
            595,780,-596
            
            --- scanner 4 ---
            727,592,562
            -293,-554,779
            441,611,-461
            -714,465,-776
            -743,427,-804
            -660,-479,-426
            832,-632,460
            927,-485,-438
            408,393,-506
            466,436,-512
            110,16,151
            -258,-428,682
            -393,719,612
            -211,-452,876
            808,-476,-593
            -575,615,604
            -485,667,467
            -680,325,-822
            -627,-443,-432
            872,-547,-609
            833,512,582
            807,604,487
            839,-516,451
            891,-625,532
            -652,-548,-490
            30,-46,-14
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '3621' => <<<'INPUT'
            --- scanner 0 ---
            404,-588,-901
            528,-643,409
            -838,591,734
            390,-675,-793
            -537,-823,-458
            -485,-357,347
            -345,-311,381
            -661,-816,-575
            -876,649,763
            -618,-824,-621
            553,345,-567
            474,580,667
            -447,-329,318
            -584,868,-557
            544,-627,-890
            564,392,-477
            455,729,728
            -892,524,684
            -689,845,-530
            423,-701,434
            7,-33,-71
            630,319,-379
            443,580,662
            -789,900,-551
            459,-707,401
            
            --- scanner 1 ---
            686,422,578
            605,423,415
            515,917,-361
            -336,658,858
            95,138,22
            -476,619,847
            -340,-569,-846
            567,-361,727
            -460,603,-452
            669,-402,600
            729,430,532
            -500,-761,534
            -322,571,750
            -466,-666,-811
            -429,-592,574
            -355,545,-477
            703,-491,-529
            -328,-685,520
            413,935,-424
            -391,539,-444
            586,-435,557
            -364,-763,-893
            807,-499,-711
            755,-354,-619
            553,889,-390
            
            --- scanner 2 ---
            649,640,665
            682,-795,504
            -784,533,-524
            -644,584,-595
            -588,-843,648
            -30,6,44
            -674,560,763
            500,723,-460
            609,671,-379
            -555,-800,653
            -675,-892,-343
            697,-426,-610
            578,704,681
            493,664,-388
            -671,-858,530
            -667,343,800
            571,-461,-707
            -138,-166,112
            -889,563,-600
            646,-828,498
            640,759,510
            -630,509,768
            -681,-892,-333
            673,-379,-804
            -742,-814,-386
            577,-820,562
            
            --- scanner 3 ---
            -589,542,597
            605,-692,669
            -500,565,-823
            -660,373,557
            -458,-679,-417
            -488,449,543
            -626,468,-788
            338,-750,-386
            528,-832,-391
            562,-778,733
            -938,-730,414
            543,643,-506
            -524,371,-870
            407,773,750
            -104,29,83
            378,-903,-323
            -778,-728,485
            426,699,580
            -438,-605,-362
            -469,-447,-387
            509,732,623
            647,635,-688
            -868,-804,481
            614,-800,639
            595,780,-596
            
            --- scanner 4 ---
            727,592,562
            -293,-554,779
            441,611,-461
            -714,465,-776
            -743,427,-804
            -660,-479,-426
            832,-632,460
            927,-485,-438
            408,393,-506
            466,436,-512
            110,16,151
            -258,-428,682
            -393,719,612
            -211,-452,876
            808,-476,-593
            -575,615,604
            -485,667,467
            -680,325,-822
            -627,-443,-432
            872,-547,-609
            833,512,582
            807,604,487
            839,-516,451
            891,-625,532
            -652,-548,-490
            30,-46,-14
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $scannersInput = explode("\n\n", $input);
        $scanners = [];

        foreach ($scannersInput as $index => $scannerInput) {
            $scannerInput = explode("\n", $scannerInput);
            array_shift($scannerInput);

            $scanners[$index] = new Scanner($index);

            foreach ($scannerInput as $beaconIndex => $coords) {
                $scanners[$index]->beacons[$beaconIndex]['coords'] = new Point3(
                    ...array_map('intval', explode(',', $coords))
                );
            }
        }

        foreach ($scanners as $scanner) {
            $scanner->calculateDistancesBetweenBeacons();
        }

        $scanner0 = $scanners[0];
        unset($scanners[0]);

        while (count($scanners) > 0) {
            foreach ($scanner0->beacons as $beacon) {
                foreach ($scanners as $scannerIndex => $scanner){
                    $countBeacons = count($scanner->beacons);

                    foreach ($scanner->beacons as $beacon2) {
                        $intersectingBeacons = array_intersect($beacon['distance'], $beacon2['distance']);

                        if (count($intersectingBeacons) >= 11) {

                            $distance = current($intersectingBeacons);
                            $s0b1 = $beacon['coords'];
                            $secondIndex = array_search($distance, $beacon['distance'], true);
                            $s0b2 = $scanner0->beacons[$secondIndex]['coords'];
                            $s0distances = [
                                'x' => $s0b2->x - $s0b1->x,
                                'y' => $s0b2->y - $s0b1->y,
                                'z' => $s0b2->z - $s0b1->z,
                            ];
                            $s0distances2 = array_map(fn ($a) => $a ** 2, $s0distances);

                            $s1b1 = $beacon2['coords'];
                            $secondIndex = array_search($distance, $beacon2['distance'], true);
                            $s1b2 = $scanner->beacons[$secondIndex]['coords'];

                            $s1distances = [
                                'x' => $s1b2->x - $s1b1->x,
                                'y' => $s1b2->y - $s1b1->y,
                                'z' => $s1b2->z - $s1b1->z,
                            ];
                            $s1distances2 = array_map(fn ($a) => $a ** 2, $s1distances);

                            $map = [];

                            foreach (['x', 'y', 'z'] as $coordinate) {
                                $mappedCoordinate = array_search($s0distances2[$coordinate], $s1distances2, true);
                                $inversed = $s0distances[$coordinate] !== $s1distances[$mappedCoordinate];

                                $scanner->coordinates->{$coordinate} = $inversed
                                    ? $s0b1->{$coordinate} + $s1b1->{$mappedCoordinate}
                                    : $s0b1->{$coordinate} - $s1b1->{$mappedCoordinate};

                                $map[$coordinate] = [
                                    $mappedCoordinate,
                                    $inversed,
                                ];
                            }

                            foreach ($scanner->beacons as $scannerBeacon) {
                                $coords = new Point3();

                                foreach ($map as $coordinate => [$mappedCoordinate, $inversed]) {
                                    $coords->{$coordinate} = $inversed
                                        ? $scanner->coordinates->{$coordinate} - $scannerBeacon['coords']->{$mappedCoordinate}
                                        : $scanner->coordinates->{$coordinate} + $scannerBeacon['coords']->{$mappedCoordinate};
                                }

                                $exists = false;

                                foreach ($scanner0->beacons as $beacon) {
                                    if ($beacon['coords']->equalsTo($coords)) {
                                        $exists = true;
                                        break;
                                    }
                                }

                                if (!$exists) {
                                    $scanner0->beacons[] = ['coords' => $coords];
                                }
                            }

                            $scanner0->calculateDistancesBetweenBeacons();
                            unset($scanners[$scannerIndex]);

                            break 3;
                        }

                        if (--$countBeacons < 12) {
                            break;
                        }
                    }
                }
            }
        }

        return count($scanner0->beacons);
    }

    public function solvePart2(string $input): string|int
    {
        $scannersInput = explode("\n\n", $input);
        $scanners = [];

        foreach ($scannersInput as $index => $scannerInput) {
            $scannerInput = explode("\n", $scannerInput);
            array_shift($scannerInput);

            $scanners[$index] = new Scanner($index);

            foreach ($scannerInput as $beaconIndex => $coords) {
                $scanners[$index]->beacons[$beaconIndex]['coords'] = new Point3(
                    ...array_map('intval', explode(',', $coords))
                );
            }
        }

        foreach ($scanners as $scanner) {
            $scanner->calculateDistancesBetweenBeacons();
        }

        $allScanners = $scanners;
        $scanner0 = $scanners[0];
        unset($scanners[0]);

        while (count($scanners) > 0) {
            foreach ($scanner0->beacons as $beacon) {
                foreach ($scanners as $scannerIndex => $scanner){
                    $countBeacons = count($scanner->beacons);

                    foreach ($scanner->beacons as $beacon2) {
                        $intersectingBeacons = array_intersect($beacon['distance'], $beacon2['distance']);

                        if (count($intersectingBeacons) >= 11) {

                            $distance = current($intersectingBeacons);
                            $s0b1 = $beacon['coords'];
                            $secondIndex = array_search($distance, $beacon['distance'], true);
                            $s0b2 = $scanner0->beacons[$secondIndex]['coords'];
                            $s0distances = [
                                'x' => $s0b2->x - $s0b1->x,
                                'y' => $s0b2->y - $s0b1->y,
                                'z' => $s0b2->z - $s0b1->z,
                            ];
                            $s0distances2 = array_map(fn ($a) => $a ** 2, $s0distances);

                            $s1b1 = $beacon2['coords'];
                            $secondIndex = array_search($distance, $beacon2['distance'], true);
                            $s1b2 = $scanner->beacons[$secondIndex]['coords'];

                            $s1distances = [
                                'x' => $s1b2->x - $s1b1->x,
                                'y' => $s1b2->y - $s1b1->y,
                                'z' => $s1b2->z - $s1b1->z,
                            ];
                            $s1distances2 = array_map(fn ($a) => $a ** 2, $s1distances);

                            $map = [];

                            foreach (['x', 'y', 'z'] as $coordinate) {
                                $mappedCoordinate = array_search($s0distances2[$coordinate], $s1distances2, true);
                                $inversed = $s0distances[$coordinate] !== $s1distances[$mappedCoordinate];

                                $scanner->coordinates->{$coordinate} = $inversed
                                    ? $s0b1->{$coordinate} + $s1b1->{$mappedCoordinate}
                                    : $s0b1->{$coordinate} - $s1b1->{$mappedCoordinate};

                                $map[$coordinate] = [
                                    $mappedCoordinate,
                                    $inversed,
                                ];
                            }

                            foreach ($scanner->beacons as $scannerBeacon) {
                                $coords = new Point3();

                                foreach ($map as $coordinate => [$mappedCoordinate, $inversed]) {
                                    $coords->{$coordinate} = $inversed
                                        ? $scanner->coordinates->{$coordinate} - $scannerBeacon['coords']->{$mappedCoordinate}
                                        : $scanner->coordinates->{$coordinate} + $scannerBeacon['coords']->{$mappedCoordinate};
                                }

                                $exists = false;

                                foreach ($scanner0->beacons as $beacon) {
                                    if ($beacon['coords']->equalsTo($coords)) {
                                        $exists = true;
                                        break;
                                    }
                                }

                                if (!$exists) {
                                    $scanner0->beacons[] = ['coords' => $coords];
                                }
                            }

                            $scanner0->calculateDistancesBetweenBeacons();
                            unset($scanners[$scannerIndex]);

                            break 3;
                        }

                        if (--$countBeacons < 12) {
                            break;
                        }
                    }
                }
            }
        }

        $max = 0;

        foreach ($allScanners as $scanner) {
            foreach ($allScanners as $scanner2) {
                $dist = abs($scanner->coordinates->x - $scanner2->coordinates->x)
                    + abs($scanner->coordinates->y - $scanner2->coordinates->y)
                    + abs($scanner->coordinates->z - $scanner2->coordinates->z);

                $max = max($max, $dist);
            }
        }

        return $max;
    }
}
