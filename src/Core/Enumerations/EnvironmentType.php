<?php

namespace Assegai\Core\Enumerations;

enum EnvironmentType: string
{
  case LOCAL = 'LOCAL';
  case DEVELOP = 'DEV';
  case QA = 'QA';
  case STAGING = 'STAGING';
  case PRODUCTION = 'PROD';
}