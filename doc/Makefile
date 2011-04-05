                                                                                                                                                               
all: pdf

clean:
	-rm *.aux *.log *.dvi *.blg mn.p*

pdf: mn.pdf

mn.bbl: mn.tex references.bib
	bubble -f mn.tex references.bib

mn.dvi: clean mn.tex mn.bbl
	latex mn.tex
	latex mn.tex
	latex mn.tex

mn.ps: mn.dvi
	\dvips -t a4 -o mn.ps mn.dvi

mn.pdf: mn.dvi
	\dvipdf -sPAPERSIZE=a4 mn.dvi

