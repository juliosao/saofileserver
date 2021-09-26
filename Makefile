TARGET=sao-file-server
VERSION=0.7
SECTION=Web Software
ARCHITECTURE=all
DEPENDS=apache2, php, php-common, php-json, mariadb-server
DESCRIPTION=SAO File server is a tiny web file server
MAINTAINER=Julio A. Garcia Lopez
MAIL=juliosao@gmail.com

# Installation sources and destination
SOURCES=fileserver
BUILDCMD=echo "No hace falta compilar nada"
BUILDOUTPUT=fileserver
INSROOT=/var/www/html/sfs

# For building .tgz
TARNAME=$(TARGET)-$(VERSION).tgz

# For building .deb filesS
DEBTMPPATH=$(TARGET)-$(VERSION)
DEBCTRLDIR=$(TARGET)-$(VERSION)/DEBIAN
DEBCTRLFILE=$(TARGET)-$(VERSION)/DEBIAN/control
PHPFILES=$(shell find . -name *.php)

.phony:help clean deb

test:
	for i in $(PHPFILES); do \
		php -l $$i; if test $$? -ne 0; then exit 1; fi; done

help:
	@echo "USE:"
	@echo "make help: Show this help"
	@echo "make clean: Cleans all building results/intermediates"
	@echo "make tgz: Creates a tgz with sao-file-server"
	@echo "make deb: Generate web package"

clean:
	-rm -f $(TARNAME)
	-rm -rf $(DEBTMPPATH)*
	find . -name '~*' -delete

$(BUILDOUTPUT): $(SOURCES)
	$(BUILDCMD)

tgz: clean $(BUILDOUTPUT)
	tar cvzf $(TARNAME) $(BUILDOUTPUT) --exclude=.svn --exclude=.git

deb: clean $(BUILDOUTPUT)
	mkdir -p $(DEBTMPPATH)$(INSROOT)
	cp -rf $(BUILDOUTPUT)/* $(DEBTMPPATH)/$(INSROOT)
	mkdir -p $(DEBCTRLDIR)
	@echo Package: $(TARGET) > $(DEBCTRLFILE)
	@echo Version: $(VERSION) >> $(DEBCTRLFILE)
	@echo Section: $(SECTION) >> $(DEBCTRLFILE)
	@echo Architecture: $(ARCHITECTURE) >> $(DEBCTRLFILE)
	@echo Depends: $(DEPENDS) >> $(DEBCTRLFILE)
	@echo Maintainer: $(MAINTAINER) $(MAIL)  >> $(DEBCTRLFILE)
	@echo Description: $(DESCRIPTION)  >> $(DEBCTRLFILE)
	
	fakeroot dpkg-deb --build $(DEBTMPPATH)


