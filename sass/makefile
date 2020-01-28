all:
	

# Prepare a SASS installation, this should allow any system to build the assets
# for this project on it's own.
install:
	echo 'Installing Dart SASS 1.23.0'
	wget https://github.com/sass/dart-sass/releases/download/1.23.0/dart-sass-1.23.0-linux-x64.tar.gz  --quiet
	gunzip -f dart-sass-1.23.0-linux-x64.tar.gz
	tar -xvf dart-sass-1.23.0-linux-x64.tar --strip-components=1 -C .

clean : 
	-rm dart-sass-1.23.0-linux-x64.tar.gz
	-rm dart-sass-1.23.0-linux-x64.tar

remove:
	make clean
	-rm -R sass
	-rm -R dart-sass
	-rm -R src