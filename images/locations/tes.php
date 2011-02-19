<?php
/*
* Tiger Laundry: Test Location Drawing Page
*
* Design by Benjamin Russell (www.csh.rit.edu/~benrr101)
* Project supported by RIT Residence Halls Association (rha.rit.edu)
*
*/

// Create the object
$room = new room("tes", 240, 240);
$room->addMachine("wash", 4, 4, 3);
$room->addMachine("dry", 3, 0, 1);
$room->addMachine("dry", 2, 2, 1, false, true);
$room->addMachine("wash", 1, 4, 1);
$room->drawRoom();
?>