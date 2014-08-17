<?php
namespace Moderator\Entity;


interface StageInterface {

    const STAGE_NEW = 1;
    const STAGE_WAITING = 2;
    const STAGE_IN_PROCESS = 3;
    const STAGE_REOPENED = 4;
    const STAGE_DONE = 5;
    const STAGE_CANCELED = 6;
}