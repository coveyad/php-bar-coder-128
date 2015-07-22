# php-bar-coder-128

Basic PHP functions and font for creating Code 128 bar codes intended to offer an open source,
free of charge, starting point for those wanting to utilize text/font based Code 128 bar codes for print, 
web pages, and/or applications.

Many excellent implementations exist for these same purposes, so why would anyone be intrested in this one?
Because...

• php-bar-coder-128 is tiny, simple, and easy to adjust if something isn't done to taste
• php-bar-coder-128's included font has bar codes from 0x0020-0x007e / 0x00c3-0x00cf, and also from 
  0xf020-0xf07e / 0xf0c3-0xf0cf (this seems to help it work correctly across various platforms)
• php-bar-coder-128 does not create images (all php-bar-coder-128 bar codes are strings displayed using its
  included font FreeCode128)
  
A brief note on usage:

• When using in HTML documents, be certain that the browser knows the bar code strings are not in the UTF-8 format.
  Using the PHP function to generate s string while instructing the browser to display that string as UTF-8 will
  result in characters appearing that have no bar code mappings.  Therefore, if something like: 
  <meta charset="UTF-8"> is found on a webpage, the font will not apply correctly to the string generated by 
  the PHP function.
