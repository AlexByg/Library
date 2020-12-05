function checkForm(form)
{
    let elements = $(form).find("[data-type]");
    let bad = "";
    for (let i = 0; i < elements.length; i++) {
        bad += checkElement(elements.get(i));
    }

    if (bad === "") {
        let t_confirm = $(form).find(["data-tconfirm"]).attr("data-tconfirm");

        if (t_confirm) {
            return confirm(t_confirm);
        }

        return true;
    }

    alert(bad);

    return false;
}

function checkElement(element)
{
    let type = $(element).attr("data-type");
    let min_len = $(element).attr("data-minlen");
    let max_len = $(element).attr("data-maxlen");
    let t_min_len = $(element).attr("data-tminlen");
    let t_max_len = $(element).attr("data-tmaxlen");
    let min_val = $(element).attr("data-minval");
    let max_val = $(element).attr("data-maxval");
    let t_empty = $(element).attr("data-tempty");
    let t_type = $(element).attr("data-ttype");

    let bad = "";

    if (type === "") {
        bad += checkTextInput($(element).val(), min_len, max_len, t_empty, t_min_len, t_max_len, t_type);
    } else if (type == "name") {
        bad += checkName($(element).val(), max_len, t_empty, t_type, t_max_len);
    } else if (type == "number") {
        bad += checkYear($(element).val(), t_empty, min_val, max_val, t_type);
    }

    return bad;
}

function checkTextInput(value, min_len, max_len, t_empty, t_min_len, t_max_len, t_type)
{
    if (value.length === 0) {
        return t_empty + "\n";
    } else {
        if (max_len && value.length > max_len) {
            return t_max_len + "\n";
        }

        if (min_len && value.length < min_len) {
            return t_min_len + "\n";
        }

        if (isContainQuotes(value)) {
            return t_type + "\n";
        }
    }

    return "";
}

function checkName(name, max_len, t_empty, t_type, t_max_len)
{
    if (name.length === 0) {
        return t_empty + "\n";
    }

    if (name.length > max_len) {
        return t_max_len + "\n";
    }

    if (t_type && isContainQuotes(name)) {
        return t_type + "\n";
    }

    return "";
}

function checkYear(year, t_empty, min_val, max_val, t_type)
{
    if (year.length === 0) {
        return t_empty + "\n";
    } else if (isNaN(year)) {
        return t_type + "\n";
    }

    year = +year;

    if (Number.isInteger(year)) {
        if ((year < min_val) || (year > max_val)) {
            return t_type + "\n";
        }
    } else {
        return t_type + "\n";
    }

    return "";
}

function isContainQuotes(string)
{
    let array = new Array("\"", "'", "`", "&quot;", "&apos;");

    for (let i = 0; i < array.length; i++) {
        if (string.indexOf(array[i]) !== -1) {
            return true;
        }
    }

    return false;
}
