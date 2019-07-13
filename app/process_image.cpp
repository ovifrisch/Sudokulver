#include "opencv/cv.hpp"
#include "opencv/ml.h"
#include <string>
#include <vector>
#include <iostream>
#include <stdlib.h>
#include "math.h"
#include <algorithm>

using namespace cv;
using namespace std;

int NUM_FEATS = 64;
Mat color_image, gray_image, color_grid, gray_grid;
int intersections[10][10][2];

struct debug_params {
	int num_test_images = 13;
} debug;

struct tweaking_parameters {
	int hough_threshold = 150;
	int erosion_struct_size = 30;
	int thresh = 15;
} tweak;

struct twoPt {
	int x1;
	int y1;
	int x2;
	int y2;
};

struct Line {
	float slope;
	float intercept;
};

bool point_comparator(Vec3f& pt1, Vec3f& pt2) {
	if (pt1[2] < pt2[2]) return true;
	else return false;
}

void print_lines(vector<Vec3f>& lines, int val) {
	for (int i = 0; i < lines.size(); i++) {
		float rho1 = lines[i][0];
		float theta1 = lines[i][1];
		Point p1, p2;
		double a1 = cos(theta1), b1 = sin(theta1);
		double x01 = a1*rho1, y01 = b1*rho1;
		p1.x = cvRound(x01 + 1000 * (-b1));
		p1.y = cvRound(y01 + 1000 * (a1));
		p2.x = cvRound(x01 - 1000 * (-b1));
		p2.y = cvRound(y01 - 1000 * (a1));
		line(color_grid, p1, p2, Scalar(0, val, 255), 2);
	}
	imshow("im", color_grid);
	waitKey(0);
}


Mat dilateImage(Mat im, int size_x, int size_y) {
	int dilation_type = MORPH_RECT;
	Mat element = getStructuringElement( dilation_type,
									   Size( 2*size_x + 1, 2*size_y + 1));
	Mat res;
	dilate( im, res, element );
	return res;
}


Mat erodeImage(Mat im, int size_x, int size_y) {
	int erosion_type = MORPH_RECT;
	Mat element = getStructuringElement(erosion_type,
										Size(2*size_x + 1, 2*size_y + 1));
	Mat res;
	erode(im, res, element);
	return res;
}

void pretty_print(float labels[9][9]) {
	for (int i = 0; i < 3; i++) {
		int row = i*3;
		for (int j = 0; j < 3; j++) {
			int col = j*3;
			for (int k = 0; k < 3; k++) {
				for (int l = 0; l < 3; l++) {
					float val = labels[row + k][col + l];
					if (val == 0) {
						cout << " ";
					}
					else {
						cout << val;
					}
				}
			}
		}
	}
}

void extract_grid() {
	Mat edge_image, dilated_image;
	Canny(gray_image, edge_image, 250, 100);
	dilated_image = dilateImage(edge_image, 1, 1);

	Mat labels, stats, centroids;
	int num_components = connectedComponentsWithStats(dilated_image, labels, stats, centroids, 8);
	MatSize size = labels.size;
	int max_idx = 0;
	int max_area = 0;
	for (int i = 1; i < num_components; i++) {
		int area = stats.at<int>(i, CC_STAT_AREA);
		if (area > max_area) {
			max_area = area;
			max_idx = i;
		}
	}
	int left = stats.at<int>(max_idx, CC_STAT_LEFT);
	int top = stats.at<int>(max_idx, CC_STAT_TOP);
	int width = stats.at<int>(max_idx, CC_STAT_WIDTH);
	int height = stats.at<int>(max_idx, CC_STAT_HEIGHT);

	gray_grid = gray_image(Rect(left, top, width, height));
	color_grid = color_image(Rect(left, top, width, height));
}

Mat get_sobel(int dx, int dy) {
	Mat edge_im;
	Sobel(gray_grid, edge_im, 0, dx, dy, 3, 2);
	return edge_im;
}

vector<Vec2f> do_hough_lines(Mat edge_im) {
	vector<Vec2f> hough_detected_lines;
	HoughLines(edge_im, hough_detected_lines, 1, CV_PI/180.0, tweak.hough_threshold);
	return hough_detected_lines;
}

void detect_lines(vector<Vec2f>& detected_horizontals, vector<Vec2f>& detected_verticals) {
	Mat dy_sobel, dy_edge;
	Mat dx_sobel, dx_edge;

	dy_sobel = get_sobel(0, 1);
	dy_sobel = dilateImage(dy_sobel, 0, 1);
	dy_sobel = erodeImage(dy_sobel, tweak.erosion_struct_size, 0);
	Canny(dy_sobel, dy_edge, 160, 50);
	dy_edge = dilateImage(dy_edge, 0, 1);
	detected_horizontals = do_hough_lines(dy_sobel);

	dx_sobel = get_sobel(1, 0);
	dx_sobel = dilateImage(dx_sobel, 1, 0);
	dx_sobel = erodeImage(dx_sobel, 0, tweak.erosion_struct_size);
	Canny(dx_sobel, dx_edge, 160, 50);
	dx_edge = dilateImage(dx_edge, 1, 0);
	detected_verticals = do_hough_lines(dx_edge);
}

vector<Vec3f> add_absolute_rho(vector<Vec2f> lines) {
	vector<Vec3f> result;
	for (int i = 0; i < lines.size(); i++) {
		Vec3f v;
		v[0] = lines[i][0];
		v[1] = lines[i][1];
		v[2] = abs(lines[i][0]);
		result.push_back(v);
	}

	sort(result.begin(), result.end(), point_comparator);
	return result;
}

vector<Vec3f> extract_representatives(vector<Vec3f> lines) {
	vector<Vec3f> ten_lines;
	int num_lines = lines.size();
	int start_idx = 0;
	for (int i = 1; i < num_lines; i++) {
		if (lines[i][2] - lines[i - 1][2] <= tweak.thresh && i != num_lines - 1) {
			continue;
		}
		else {
			if (i < num_lines - 1) {
				ten_lines.push_back(lines[start_idx + (((i - 1) - start_idx) / 2)]);
				start_idx = i;
			}
			else {
				ten_lines.push_back(lines[start_idx + ((i - start_idx) / 2)]);
				break;
			}
		}
	}
	return ten_lines;
}

twoPt convert_to_twoPt(Vec3f line) {
	vector<twoPt> res;
	float rho1 = line[0];
	float theta1 = line[1];
	twoPt l;
	double a1 = cos(theta1), b1 = sin(theta1);
	double x01 = a1*rho1, y01 = b1*rho1;
	l.x1 = cvRound(x01 + 1000 * (-b1));
	l.y1 = cvRound(y01 + 1000 * (a1));
	l.x2 = cvRound(x01 - 1000 * (-b1));
	l.y2 = cvRound(y01 - 1000 * (a1));
	return l;
}


Point lineLineIntersection(Point A, Point B, Point C, Point D)
{
	Point p;
	// Line AB represented as a1x + b1y = c1
	double a1 = B.y - A.y;
	double b1 = A.x - B.x;
	double c1 = a1*(A.x) + b1*(A.y);

	// Line CD represented as a2x + b2y = c2
	double a2 = D.y - C.y;
	double b2 = C.x - D.x;
	double c2 = a2*(C.x)+ b2*(C.y);

	double determinant = a1*b2 - a2*b1;
	p.x = cvRound((b2*c1 - b1*c2)/determinant);
	p.y = cvRound((a1*c2 - a2*c1)/determinant);
	return p;
}

Point detect_one_intersection(Vec3f pt1, Vec3f pt2, int i, int j) {
	Point intersection;

	twoPt l1 = convert_to_twoPt(pt1);
	twoPt l2 = convert_to_twoPt(pt2);
	Point a, b, c, d;

	a.x = l1.x1;
	a.y = l1.y1;
	b.x = l1.x2;
	b.y = l1.y2;
	c.x = l2.x1;
	c.y = l2.y1;
	d.x = l2.x2;
	d.y = l2.y2;

	intersection = lineLineIntersection(a, b, c, d);

	if (intersection.x < 1) {
		intersection.x = 1;
	}
	if (intersection.x > gray_grid.size[1]) {
		intersection.x = gray_grid.size[1];
	}

	if (intersection.y < 1) {
		intersection.y = 1;
	}

	if (intersection.y > gray_grid.size[0]) {
		intersection.y = gray_grid.size[0];
	}

	return intersection;
}

void detect_intersections(Point intersections[10][10], vector<Vec3f> ten_horizontals, vector<Vec3f> ten_verticals) {
	for (int i = 0; i < 10; i++) {
		for (int j = 0; j < 10; j++) {
			intersections[i][j] = detect_one_intersection(ten_horizontals[i], ten_verticals[j], i, j);
		}
	}
}

void show_intersections(Point intersection[10][10]) {
	for (int i = 0; i < 10; i++) {
		for (int j = 0; j < 10; j++) {
			circle(color_grid, intersection[i][j], 2, Scalar(255, 255, 0), 2);
		}
	}
	imshow("im", color_grid);
	waitKey(0);

}

void extract_squares(Point intersections[10][10], Mat squares[9][9]) {
	for (int i = 0; i < 9; i++) {
		for (int j = 0; j < 9; j++) {
			int left = intersections[i][j].x;
			int top = intersections[i][j].y;
			int width = intersections[i][j + 1].x - left;
			int height = intersections[i + 1][j].y - top;
			Mat square = color_grid(Rect(left, top, width, height));
			squares[i][j] = square;
		}
	}
}

Mat get_features(Mat im) {
	Mat features(1, NUM_FEATS, CV_32FC1);
	//HOGDescriptor hog;
	//hog.winSize = Size(64, 64);
	HOGDescriptor hog(Size(64, 64), Size(64, 64), Size(64, 64), Size(16, 16), 4);
	vector<float> descriptors;
	hog.compute(im, descriptors);
	//copy the features
	for (int i = 0; i < descriptors.size(); i++) {
		features.at<float>(0, i) = descriptors[i];
	}
	return features;
}

Mat prepare_image(Mat im) {
	Mat new_im;
	resize(im, new_im, Size(80, 80));
	Mat edge_square;
	Canny(new_im, edge_square, 180, 50);
	edge_square = edge_square(Rect(8, 8, 64, 64));
	return edge_square;
}

float detect_one_digit(Ptr<ml::SVM> svm, Mat im) {
	Mat new_im = prepare_image(im);
	Mat instance(1, NUM_FEATS, CV_32FC1);
	instance = get_features(new_im);
	Mat res;
	float response = svm->predict(instance);
	return response;
}

void detect_digits(Mat squares[9][9], float labels[9][9]) {
	Ptr<ml::SVM> svm = ml::SVM::load("new_svm.xml");
	for (int i = 0; i < 9; i++) {
		for (int j = 0; j < 9; j++) {
			labels[i][j] = detect_one_digit(svm, squares[i][j]);
		}
	}
}

void see_print(float labels[9][9]) {
	for (int i = 0; i < 9; i++) {
		for (int j = 0; j < 9; j++) {
			cout << labels[i][j] << " ";
		}
		cout << endl;
	}
	cout << endl << endl << endl;
}

void error_print() {
	for (int i = 0; i < 81; i++) {
		cout << " ";
	}
	exit(1);
}


Mat resize_im(Mat im) {
	Mat resized_im;

	float height = im.size[0];
	float width = im.size[1];

	float new_width = 500;
	float new_height = (height / width) * 500;

	resize(im, resized_im, Size(new_width, new_height));

	return resized_im;
}


int main() {
	string im_name = "uploads/im.jpeg";
	Mat big_color_image = imread(im_name, IMREAD_COLOR);
	color_image = resize_im(big_color_image);
	cvtColor(color_image, gray_image, CV_BGR2GRAY);
	extract_grid();
	vector<Vec2f> detected_horizontals, detected_verticals;
	detect_lines(detected_horizontals, detected_verticals);
	vector<Vec3f> horizontals = add_absolute_rho(detected_horizontals);
	vector<Vec3f> verticals = add_absolute_rho(detected_verticals);
	vector<Vec3f> ten_horizontals = extract_representatives(horizontals);
	vector<Vec3f> ten_verticals = extract_representatives(verticals);
	if (ten_verticals.size() != 10 || ten_horizontals.size() != 10) error_print();
	Point intersections[10][10];
	detect_intersections(intersections, ten_horizontals, ten_verticals);
	Mat squares[9][9];
	extract_squares(intersections, squares);
	float labels[9][9];
	detect_digits(squares, labels);
	pretty_print(labels);
}
