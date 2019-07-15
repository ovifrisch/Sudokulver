class Grid {
	constructor() {
		var i;
		this.rows = new Array();
		this.cols = new Array();
		this.boxes = new Array();
		for (i = 0; i < 9; i++) {
			this.rows[i] = new Line();
			this.cols[i] = new Line();
			this.boxes[i] = new Box();
		}
	}

	add_square_to_grid(row, col, val, final) {
		this.rows[row].add_square(col, val, final);
		this.cols[col].add_square(row, val, final);
		this.boxes[get_box_index(row, col)].add_square((3 * (row % 3) ) + (col % 3), val, final);
	}
}
