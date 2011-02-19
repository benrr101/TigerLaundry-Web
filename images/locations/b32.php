<?php
/*
* Tiger Laundry: Bldg 32 Location Drawing Page
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

// Create the room
$room = new room("b32", 240, 510);
$room->addDoor(0, 5, 1);
// ----------- Washers
$room->addMachine("wash", 5, 1, 0);
$room->addMachine("wash", 6, 3, 0);
$room->addMachine("wash", 7, 5, 0);
$room->addMachine("wash", 8, 7, 0);
$room->addMachine("wash", 9, 9, 0);
$room->addMachine("wash", 10,11,0);
// ----------- Dryers
$room->addMachine("dry", 11, 15, 1);
$room->addMachine("dry", 12, 14, 1, false);
$room->addMachine("dry", 13, 15, 3);
$room->addMachine("dry", 14, 14, 3, false);
$room->addMachine("dry", 15, 15, 5);
$room->addMachine("dry", 16, 14, 5, false);

// Draw it
$room->drawRoom();

?>
