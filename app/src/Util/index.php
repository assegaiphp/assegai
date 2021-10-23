<?php

namespace LifeRaft\Util;

function isMatch(string $pattern, string $subject, string $delimiter = '/'): bool
{
  if ($pattern === $subject)
  {
    return true;
  }

  $patternTokens = explode( $delimiter, trim( $pattern, $delimiter ) );
  $subjectTokens = explode( $delimiter, trim( $subject, $delimiter ) );

  $score = 0;

  if (empty($patternTokens) && empty($subjectTokens))
  {
    return true;
  }

  foreach ($subjectTokens as $index => $sToken)
  {
    if ( ( isset($patternTokens[$index]) && $sToken === $patternTokens[$index] ) || $sToken === '*')
    {
      $score++;
    }
  }

  return $score === count($subjectTokens);
}
