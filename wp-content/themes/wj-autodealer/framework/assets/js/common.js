function site_url(url){
    if (url.substr(0, 7) == 'http://' || url.substr(0, 8) == 'https://') return url;
    else return theme_site_url + trim(url, '/');
}

function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

// function site_url( url ){
// 	//return 'http://wp-test' + url;
// 	//theme_site_url
// 	return url;
// }