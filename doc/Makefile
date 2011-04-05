                                                                                                                                                               
all: papers

clean:
	-rm *.aux *.log *.dvi *.blg astrotaches.ps stringsearch.ps

papers: pdf

paper1: astrotaches.pdf

paper2: stringsearch.pdf

pdf: astrotaches.pdf stringsearch.pdf

ps: astrotaches.ps stringsearch.ps

astrotaches.bbl: astrotaches.tex references.bib
	bubble -f astrotaches.tex references.bib

astrotaches.dvi: clean astrotaches.tex astrotaches.bbl
	latex astrotaches.tex
	latex astrotaches.tex
	latex astrotaches.tex
# 	bibtex astrotaches
# 	latex astrotaches.tex
# 	latex astrotaches.tex

astrotaches.ps: astrotaches.dvi
	\dvips -t a4 -o astrotaches.ps astrotaches.dvi

astrotaches.pdf: astrotaches.ps
	ps2pdf14 -dPDFsettings=/prepress astrotaches.ps &

# stringsearch.pdf: clean stringsearch.tex
#	bubble -f stringsearch.tex references.bib
#	pdflatex stringsearch.tex
#	pdflatex stringsearch.tex
#	pdflatex stringsearch.tex
#
# stringsearch.ps: stringsearch.pdf
#	pdf2ps stringsearch.pdf

stringsearch.bbl: stringsearch.tex references.bib
	bubble -f stringsearch.tex references.bib

stringsearch.dvi: clean stringsearch.tex stringsearch.bbl
	latex stringsearch.tex
	latex stringsearch.tex
	latex stringsearch.tex
# 	bibtex stringsearch
# 	latex stringsearch.tex
# 	latex stringsearch.tex

stringsearch.ps: stringsearch.dvi
	\dvips -t a4 -o stringsearch.ps stringsearch.dvi

stringsearch.pdf: stringsearch.ps
	ps2pdf14 -dPDFsettings=/prepress stringsearch.ps &
