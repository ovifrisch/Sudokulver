# PHP and Apache Web Server
FROM php:7.2-apache

# Install OpenCV
RUN apt-get update && apt-get install -y \
	build-essential \
	cmake \
	git \
	libgtk2.0-dev \
	pkg-config \
	libavcodec-dev \
	libavformat-dev \
	libswscale-dev \
	python-dev \
	python-numpy \
	libtbb2 \
	libtbb-dev \
	libjpeg-dev \
	libpng-dev \
	libtiff-dev \
	libdc1394-22-dev
	
WORKDIR /var/www/html
RUN git clone https://github.com/opencv/opencv.git
RUN git clone https://github.com/opencv/opencv_contrib.git

WORKDIR /var/www/html/opencv
RUN mkdir build

WORKDIR /var/www/html/opencv/build
RUN cmake -D CMAKE_BUILD_TYPE=Release -D OPENCV_GENERATE_PKGCONFIG=YES -D CMAKE_INSTALL_PREFIX=/usr/local ..
RUN make -j4
RUN make install

# Copy your local files
COPY web/ /var/www/html/

# Compile C++ source
WORKDIR /var/www/html
RUN g++ -std=c++11 -o process_image $(pkg-config --cflags --libs opencv4) process_image.cpp
RUN ldconfig
# Replace port 80 with $PORT everywhere
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-enabled/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground
