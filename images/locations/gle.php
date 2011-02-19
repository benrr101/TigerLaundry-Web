<?php
/*
* Tiger Laundry: Test Location Drawing Page
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

// Create the room
$room = new room('gle', 510, 690);
$room->addDoor(0, 21, 1);

// Washers -------------
$room->addMachine("wash", 17, 0, 17);
$room->addMachine("wash", 18, 2, 17);
$room->addMachine("wash", 19, 4, 17);
$room->addMachine("wash", 20, 6, 17);
$room->addMachine("wash", 21, 10, 17);
//--
$room->addMachine("wash", 22, 0, 15);
$room->addMachine("wash", 23, 2, 15);
$room->addMachine("wash", 24, 4, 15);
$room->addMachine("wash", 25, 6, 15);
$room->addMachine("wash", 26, 8, 15);
$room->addMachine("wash", 27, 10, 15);
//--
$room->addMachine("wash", 28, 0, 9);
$room->addMachine("wash", 29, 2, 9);
$room->addMachine("wash", 30, 4, 9);
$room->addMachine("wash", 31, 6, 9);
$room->addMachine("wash", 32, 9, 9);
//--
$room->addMachine("wash", 33, 0, 7);
$room->addMachine("wash", 34, 2, 7);
$room->addMachine("wash", 35, 4, 7);
$room->addMachine("wash", 36, 6, 7);
$room->addMachine("wash", 37, 8, 7);
$room->addMachine("wash", 38, 10, 7);

?>
