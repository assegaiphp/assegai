<?php

namespace LifeRaft\Util;

function is_match(string $pattern, string $subject, string $delimiter = '/'): bool
{
  if ($pattern === $subject)
  {
    return true;
  }

  $pattern_tokens = explode( $delimiter, trim( $pattern, $delimiter ) );
  $subject_tokens = explode( $delimiter, trim( $subject, $delimiter ) );

  $score = 0;

  if (empty($pattern_tokens) && empty($subject_tokens))
  {
    return true;
  }

  foreach ($subject_tokens as $index => $s_token)
  {
    if ( ( isset($pattern_tokens[$index]) && $s_token === $pattern_tokens[$index] ) || $s_token === '*')
    {
      $score++;
    }
  }

  return $score === count($subject_tokens);
}
