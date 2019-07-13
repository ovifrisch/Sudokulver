
/*GLOBALS*/

var grid;
var grid_state = 0; //0 : not yet set. 1 : valid solution. -1/-2 : invalid solution
var boxes_selected = 0;

function handleFiles(files) {
    var selectedFile = files[0].name;
    console.log(selectedFile);
}


/* solve selected event handler*/
function solve_selected() {
    if (grid_state == 0 || boxes_selected == 0)
        return;

    if (grid_state == -1 || grid_state == -2) {
        grid_error_handler(grid_state);
        return;
    }

    var squares = document.getElementsByClassName("square");
    var selected_squares = [];
    var i;
    for (i = 0; i < squares.length; i++) {
        if (squares[i].style.backgroundColor == "yellow") {
            selected_squares.push(squares[i].firstElementChild);
            squares[i].classList.add("yellow");
            set_background_color(squares[i], "transparent");
        }
    }

    var class_list, i, row, col;
    for (i = 0; i < selected_squares.length; i++) {
        class_list = selected_squares[i].className.split(' ');
        row = class_list[1][3];
        col = class_list[2][3];
        selected_squares[i].value = grid.rows[Number(row)].vals[Number(col)].num
        grid.rows[Number(row)].vals[Number(col)].final = true;
    }

    document.getElementsByClassName("solve")[0].classList.add("action");
    document.getElementsByClassName("selected")[0].classList.remove("action");
    boxes_selected = 0;
}

function set_squares() {
    var squares = document.getElementsByClassName("square");
    for (i = 0; i < squares.length; i++) {
        class_list = squares[i].firstElementChild.className.split(' ');
        row = class_list[1][3];
        col = class_list[2][3];
        squares[i].firstElementChild.value = values[i];
    }
}

/* solve all event handler*/
function solve()
{
    if (grid_state == 0 || boxes_selected > 0)
        return;

    if (grid_state == -1 || grid_state == -2) {
        grid_error_handler(grid_state);
        return;
    }

    var squares = document.getElementsByClassName("square");
    var i, class_list, row, col;
    var yellow = 0;
    for (i = 0; i < squares.length; i++) {
        if (squares[i].style.backgroundColor == "yellow") {
            squares[i].classList.add("yellow");
            set_background_color(squares[i], "transparent");
            yellow = 1;
        }

        class_list = squares[i].firstElementChild.className.split(' ');
        row = class_list[1][3];
        col = class_list[2][3];

        if (yellow == 0 && grid.rows[Number(row)].vals[Number(col)].final == false) {
            squares[i].classList.add("solve_all");
            squares[i].firstElementChild.classList.add("solve_all");
        }

        squares[i].firstElementChild.value = grid.rows[Number(row)].vals[Number(col)].num;
        grid.rows[Number(row)].vals[Number(col)].final = true;
        yellow = 0;
    }

    document.getElementsByClassName("solve")[0].classList.remove("action");
    document.getElementsByClassName("selected")[0].classList.remove("action");
}

/* done inputting event handler*/
function set()
{
    if (grid_state != 0)
        return;

    // document.getElementsByClassName("error")[0].classList.remove("enabled");
    var squares = document.getElementsByClassName("num_in");
    
    var att;
    for (i = 0; i < squares.length; i++) {
        squares[i].classList.add("disabled");
        squares[i].parentElement.classList.add("disabled");
        att = document.createAttribute("disabled");
        squares[i].setAttributeNode(att);
    }

    document.getElementsByClassName("solve")[0].classList.add("action");
    document.getElementsByClassName("set")[0].classList.add("inaction");

    if(initialize_grid(squares) == false) {
        grid_state = -2;
        return;
    }

    grid_state = solve_grid(grid);
}

function initialize_grid(squares)
{
    grid = new Grid();
    var i;
    var not_a_number = false;
    for (i = 0; i < squares.length; i++) {
        var val = squares[i].value;

        if (isNaN(val) || val == '0') {
            not_a_number = true;
        }

        if (val != "") {
            var class_list = squares[i].className.split(' ');
            var row = class_list[1][3];
            var col = class_list[2][3];
            if (!not_a_number) {
                grid.add_square_to_grid(Number(row), Number(col), Number(val), true);
            }
            grid.rows[Number(row)].vals[Number(col)].final = true;
        }
    }
    return !not_a_number;
}


function reset()
{
    grid_state = 0;
    var squares = document.getElementsByClassName("num_in");

    for (i = 0; i < squares.length; i++) {
        squares[i].removeAttribute("disabled");
        squares[i].parentElement.classList.remove("disabled");
        squares[i].classList.remove("disabled");
        squares[i].value = "";
        check_yellow_class_and_remove(squares[i].parentElement);
        check_solve_all_class_and_remove(squares[i]);
        check_solve_all_class_and_remove(squares[i].parentElement);
        set_background_color(squares[i].parentElement, "transparent");
    }

    document.getElementsByClassName("solve")[0].classList.remove("action");
    document.getElementsByClassName("selected")[0].classList.remove("action");
    document.getElementsByClassName("set")[0].classList.remove("inaction");
    boxes_selected = 0;
}

function square_click(element)
{
    check_yellow_class_and_remove(element);

    if (grid_state == 0) {
        element.firstElementChild.focus();
        return;
    }

    var class_list = element.firstElementChild.className.split(' ');
    var row = class_list[1][3];
    var col = class_list[2][3];
    if (grid.rows[Number(row)].vals[Number(col)].final == true) {
        return;
    }

    var og_boxes_selected = boxes_selected;

    var background_color;
    if (element.style.backgroundColor == "yellow") {
        background_color = "transparent";
        boxes_selected--;
    } else {
        background_color = "yellow";
        boxes_selected++;
    }

    if (og_boxes_selected == 0) {
        document.getElementsByClassName("selected")[0].classList.add("action");
        document.getElementsByClassName("solve")[0].classList.remove("action");
    }
    else if (boxes_selected == 0) {
        document.getElementsByClassName("selected")[0].classList.remove("action");
        document.getElementsByClassName("solve")[0].classList.add("action");
    }

    set_background_color(element, background_color);
}

function set_background_color(element, value)
{
    element.style.backgroundColor = value;
    element.firstElementChild.style.backgroundColor = value;
}

function check_yellow_class_and_remove(element) {

    var class_list = element.className.split(' ');
    var i;
    for (i = 0; i < class_list.length; i++) {
        if (class_list[i] == "yellow") {
            element.classList.remove("yellow");
        }
    }
}

function check_solve_all_class_and_remove(element) {

    var class_list = element.className.split(' ');
    var i;
    for (i = 0; i < class_list.length; i++) {
        if (class_list[i] == "solve_all") {
            element.classList.remove("solve_all");
        }
    }
}

function grid_error_handler(grid_state) {
    reset();
    //document.getElementsByClassName("error")[0].classList.add("enabled");
}
