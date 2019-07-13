function is_grid_illegal(grid)
{
	var i, j;
	for (i = 0; i < 9; i++) {

		for (j = 0; j < 9; j++) {

			if (grid.rows[i].vals[j].num == 0)
				continue;

			if(is_violation(grid, i, j))
				return -1;
		}
	}

	return 0;

}



function solve_grid(grid) {
	if(is_grid_illegal(grid)) {
		return -1;
	}

	var first_slot = get_first_empty(grid);
	var current_row = first_slot.row;
	var current_col = first_slot.col;

	while (1) {
		if (current_row == first_slot.row && current_col == first_slot.col &&
			grid.rows[first_slot.row].vals[first_slot.col].num_possible == 0) { //failure
			return -1;
		}
		if (current_row == -1 && current_col == -1) { //solved
			return 1;
		}

		/*
			set to next possible
		*/
		if (set_square_to_next_possible(grid, current_row, current_col)) {
			//If no more possible, back up
			var prev = get_prev_empty(grid, current_row, current_col);
			current_row = prev.row;
			current_col = prev.col;
			continue;
		}

		/*
			Otherwise, check if there's a violation
		*/
		else {
			/*
				if no violation, advance
			*/
			if (!is_violation(grid, current_row, current_col)) {
				var next = get_next_empty(grid, current_row, current_col);

				current_row = next.row;
				current_col = next.col;
				continue;
				/*
				If there is a violation, current_row and current_col stay the same for next iteration
				where the number gets set to the next possible, whose num_possible then gets decremented.
				If it becomes zero, we back up and reset it.
				*/
			}
		}
	}
	return 1;
}

function is_violation(grid, row, col)
{
	val = grid.rows[row].vals[col].num;
	var up, down, box;

	up = grid.rows[row].contains(val, col);
	down = grid.cols[col].contains(val, row);
	box = grid.boxes[get_box_index(row, col)].contains(val, (3 * (row % 3) ) + (col % 3));

	if (!up && !down && !box)
		return false;
	return true;
}

function get_next_empty(grid, row, col)
{
	var next_empty = {};
	next_empty.row = -1;
	next_empty.col = -1;

	var col_start = col;
	var i, j;
	for (i = row; i < 9; i++) {
		if (i != row)
			col_start = 0;

		for (j = col_start; j < 9; j++) {
			if (i == row && j == col)
				continue;
			if (grid.rows[i].vals[j].final == false) {
				next_empty.row = i;
				next_empty.col = j;
				return next_empty;
			}
		}
	}
	return next_empty;
}

function set_square_to_next_possible(grid, row, col)
{
	var num_possible = grid.rows[row].vals[col].num_possible;

	if (num_possible == 0) {
		/*
			Reset it everywhere before returning
		*/
		set_num_possible(grid, row, col, 9);
		grid.add_square_to_grid(row, col, 0, false);
		return -1;
	}

	var test_num = 10 - num_possible;
	grid.add_square_to_grid(row, col, test_num, false);
	set_num_possible(grid, row, col, num_possible - 1);
	return 0;
}

function set_num_possible(grid, row, col, val)
{
	grid.rows[row].vals[col].num_possible = val;
	grid.cols[col].vals[row].num_possible = val;
	grid.boxes[get_box_index(row, col)].vals[(3 * (row % 3) ) + (col % 3)].num_possible = val;
}



function get_prev_empty(grid, row, col)
{
	var prev_empty = {};
	prev_empty.row = -1;
	prev_empty.col = -1;

	var col_start = col;
	var i, j;
	for (i = row; i >= 0; i--) {
		if (i != row)
			col_start = 8;

		for (j = col_start; j >= 0; j--) {
			if (i == row && j == col)
				continue;
			if (grid.rows[i].vals[j].final == false) {
				prev_empty.row = i;
				prev_empty.col = j;
				return prev_empty;
			}
		}
	}
	return prev_empty;

}

function get_first_empty()
{
	var i, j;
	var first_empty = {};
	for (i = 0; i < 9; i++) {
		for (j = 0; j < 9; j++) {
			if (grid.rows[i].vals[j].final == false) {
				first_empty.row = i;
				first_empty.col = j;
				return first_empty;
			}
		}
	}
}

function add_to_bias(bias, col) {
	if (col <= 2)
		return bias;
	else if (col > 2 && col <= 5)
		return bias + 1;
	else
		return bias + 2;
}

function get_box_index(row, col) {
	if (row <= 2)
		return add_to_bias(0, col);

	else if (row > 2 && row <= 5)
		return add_to_bias(3, col);

	else
		return add_to_bias(6, col);
}