<?php

function barEncode128 ( $value ) {
	
	$isTableB = TRUE;
	$isValid = TRUE;
	$returnValue = '';
	
	// make sure the string we're given at least has some characters in it, and that they are all ASCII characters...
	if ( strlen ( $value ) > 0 ) {
		
		$checkValidString = str_split( $value );
		
		foreach ( $checkValidString as $checkChar ) {
			if ( ! ( ord ( $checkChar ) >= 32 && ord ( $checkChar ) <= 126 ) )	{ $isValid = FALSE; }
		}
	
		// okay, we got some good characters
		if ( $isValid ) {
		
			$charPos = 0;
        
			while ( $charPos < strlen ( $value ) )  {
		
				if ( $isTableB ) {
			
					// See if we might benefit by switch to table c ... yes for 4 digits at start or end, else if 6 digits
					if ( $charPos == 0 || $charPos + 4 == strlen ( $value ) ) {
						$minCharPos = 4;
					} else {
						$minCharPos = 6;
					}
				
					$minCharPos = IsNumber( $value, $charPos, $minCharPos );
			
					if ( $minCharPos < 0 ) {
						// choosing table C
						
						if ( $charPos == 0 ) {
							// starting with table C
							$returnValue = chr ( 205 );
						} else {
							// switch to table C
							$returnValue = $returnValue . chr ( 199 );
						}
					
						$isTableB = FALSE;

					} else {
	
							if ( $charPos == 0 ) {
								// Starting with table B
								$returnValue = chr ( 204 );
							}	

						}
			
					} // end if ( $isTableB )
			
				if ( !$isTableB ) {

					// we are using table C, try to process 2 digits
					$minCharPos = 2;
					$minCharPos = IsNumber( $value, $charPos, $minCharPos );
				
					// we've got 2 digits, process it
					if ( $minCharPos < 0 ) {
					
						$currentChar = substr ( $value, $charPos, 2 );
						$currentChar = $currentChar < 95 ? $currentChar + 32 : $currentChar + 100;
						$returnValue = $returnValue . chr ( $currentChar );
						$charPos = $charPos + 2;
	
					} else {

					// we haven't got 2 digits, switch to table B
					$returnValue = $returnValue . chr ( 200 );
					$isTableB = TRUE;

					}
				
				} // end if ( !( $isTableB ) )
			
				if ( $isTableB ) {

					// Process 1 digit with table B
					$returnValue = $returnValue . substr ($value, $charPos, 1 );
					$charPos++;

				} // end ( $isTableB )
		
			} // end while ( $charPos < strlen ( $value ) )
		
			// now for the calculation of the checksum

			$checksum = 0;

			for ( $loop = 0; $loop < strlen ( $returnValue ); $loop++ ) {

				$currentChar = ord ( substr ( $returnValue, $loop, 2 ) );
				$currentChar = $currentChar < 127 ? $currentChar - 32 : $currentChar - 100;

				if ( $loop == 0 ) {
					$checksum = $currentChar;
				} else {
					$checksum = ( $checksum + ( $loop * $currentChar ) ) % 103;
				}

			} // end for loop
                    
			// Calculation of the checksum ASCII code
			$checksum = $checksum < 95 ? $checksum + 32 : $checksum + 100;
			// Add the checksum and the STOP
			$returnValue = $returnValue . chr ( $checksum ) . chr(206);

		} // end if ( $isValid )
	
	} // end if ( strlen ( $value ) > 0 )
	
	return $returnValue;
	
} // end function

function IsNumber ( $Value, $CharPos, $MinCharPos ) {

	// if the MinCharPos characters from CharPos are numeric, then MinCharPos = -1
	$MinCharPos--;

	if ( $CharPos + $MinCharPos < strlen ( $Value ) ) {

		while ( $MinCharPos >= 0 ) {
			
			if ( ( ord ( substr ( $Value, $CharPos + $MinCharPos, 1 ) ) < 48 ) || ( ord ( substr ( $Value, $CharPos + $MinCharPos, 1 ) ) > 57 ) ) {

				break; 
			
			}

			$MinCharPos--;

		} 
	
	}

	return $MinCharPos;

}

?>
