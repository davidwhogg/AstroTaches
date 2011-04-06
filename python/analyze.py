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
          refimage     Reference image for FITS header

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

  meanfile = 'mean.fits'
  weightfile = 'weight.fits'
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
   
  if len(args) != 2 :
      print USAGE
      return
  else :
      database = args[0]
      refimage = args[1]
      if vb: print "Analyzing paintings in",database

  # --------------------------------------------------------------------

  # Image is of size nx x ny:
  nx = 400
  ny = 400
  
  # Query database for painting image strings:
  
  host = 'localhost'

  user = ''
  password = ''

  conn = MySQLdb.Connection(db=database, host=host, user=user, passwd=password)
  mysql = conn.cursor()

  sql = r"select * from annotations"
  mysql.execute(sql)
  fields = mysql.fetchall()

  mysql.close()
  conn.close()

  print fields
  
  # --------------------------------------------------------------------

  # Reformat array of strings into portfolio of paintings:


  # --------------------------------------------------------------------

  # Make mean and weight images:

  sum = numpy.zeros(nx,ny)
  sumsq = numpy.zeros(nx,ny)
  mean = numpy.zeros(nx,ny)
  wht = numpy.zeros(nx,ny)
  if vb: print "Generating mean and weight image..."

  for i in range(Np):
    sum += portfolio[i]
    sumsq += portfolio[i]*portfolio[i]
  
  mean = sum/float(Np)
  wht = sumsq/float(Np) - sum*mean
  wht = 1.0/wht

  # --------------------------------------------------------------------

  # Write out mean and error images with same header, from 
  # machine version.

  # Read in image data and header:

  hdulist = pyfits.open(refimage)
  hdr = hdulist[0].header
  hdulist.close()
  if vb: print "Read in reference image from ",refimage

  if vb: print "Writing mean image to ",meanfile

  if os.path.exists(meanfile): os.remove(meanfile) 
  pyfits.writeto(meanfile,mean,hdr)

  if vb: print "Writing weight image to ",weight

  if os.path.exists(weightfile): os.remove(weightfile) 
  pyfits.writeto(weightfile,wht,hdr)

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


