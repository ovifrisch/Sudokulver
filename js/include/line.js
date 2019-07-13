class Line {
	constructor() {
		this.vals = new Array();

		var i;
		for (i = 0; i < 9; i++) {
			this.vals[i] = new Square();
		}
	}

	add_square(index, value, final) {
		this.vals[index].set_square(value, final);
	}

	contains(val, pos) {
		var i;
		for (i = 0; i < 9; i++) {
			if (i == pos)
				continue;

			if (this.vals[i].num == val)
				return true;
		}
		return false;
	}
}