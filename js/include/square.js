class Square {
	constructor() {
		this.num_possible = 9;
		this.num = 0;
		this.final = false;
		this.possible_vals = new Array();
	}

	set_square(value, final) {
		this.num = value;
		this.final = final;
	}
}
