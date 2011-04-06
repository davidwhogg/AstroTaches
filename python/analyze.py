#!/usr/bin/env python
# ======================================================================

# Globally useful modules, imported here and then accessible by all
# functions in this file:

import mysql,numpy,os,getopt,pyfits


# ======================================================================

def analyze(argv):

  USAGE = """
  NAME
    analyze.py

  PURPOSE
    Query the results database for painting strings, unpack into square images
    and analyze.

  COMMENTS
    
  USAGE
    analyze.py [flags] [options]  database

  FLAGS
          -u           Print this message
          -v           Be verbose

  INPUTS
          database     Name of SQL database containing painting image strings

  OPTIONAL INPUTS
          
  OUTPUTS
          files        FITS images of combined 

  EXAMPLES

    ./analyze.py

  DEPENDENCIES
    pyfits
  
  BUGS

  HISTORY
    2011-04-05 started Marshall and Hogg (Oxford)      
  """

  # --------------------------------------------------------------------

  try:
      opts, args = getopt.getopt(argv, "hv", ["help","verbose"])
  except getopt.GetoptError, err:
      # print help information and exit:
      print str(err) # will print something like "option -a not recognized"
      print USAGE
      return

  output = 'noisy.fits'
  weight = 'weight.fits'
  sky = 0
  vb = False
  for o,a in opts:
      if o in ("-v", "--verbose"):
          vb = True
      elif o in ("-h", "--help"):
          print USAGE
          return
      else:
          assert False, "unhandled option"
   
  if len(args) == 2 :
      sky =  float(args[0])
      input = args[1]
      if vb: print "Adding sky background of ",sky,"e- to image in",input
  else :
      print USAGE
      return

  # --------------------------------------------------------------------

  # Query database for painting image strings:
  
  
  
  # --------------------------------------------------------------------

  # Read in image data and header:

  hdulist = pyfits.open(input)
  hdr = hdulist[0].header
  image = hdulist[0].data
  hdulist.close()

  if vb: print "Read in image from ",input

  # --------------------------------------------------------------------

  # Generate noise realisation and add to image:

  if vb: print "Generating noise realisation..."

  rms = numpy.sqrt(image + sky)
  noise = rms*numpy.random.randn(rms.shape[0],rms.shape[1])
  dirtyimage = image + noise

  # --------------------------------------------------------------------

  # Make weight image:

  if vb: print "Generating weight image..."

  wht = 1.0/(rms*rms)

  # --------------------------------------------------------------------

  # Write out noisy and weight images with same header:

  if vb: print "Writing noisy image to ",output

  if os.path.exists(output): os.remove(output) 
  pyfits.writeto(output,dirtyimage,hdr)

  if vb: print "Writing weight image to ",weight

  if os.path.exists(weight): os.remove(weight) 
  pyfits.writeto(weight,wht,hdr)

  # --------------------------------------------------------------------

  return

# ======================================================================

# If called as a script, the python variable __name__ will be set to 
# "__main__" - so test for this, and execute the main program if so.
# Writing it like this allows the function analyze to be called
# from the python command line as well as from the unix prompt.

if __name__ == '__main__':
  analyze(sys.argv[1:])

# ======================================================================


