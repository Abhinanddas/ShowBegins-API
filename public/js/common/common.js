// Postion: topLeft, topCenter, topRight, bottomLeft, bottomCenter (default), bottomRight
// Animation values: slide,fade
function addToast(content = '', theme = 'moon', time = 3000, autohide = true, position = 'topRight', animation = 'fade') {

    var toastr = new Toastr({
        theme: fetchToastrTheme(theme),
        position: position,
        animation: animation,
        timeout: time,
        autohide: autohide,
    });
    toastr.show(content);
}

// moon, sun, ocean, grassland, rainbow
function fetchToastrTheme(theme) {

    if (theme === 'success') {
        theme = 'basic';
        return theme;
    }

    if (theme === 'info') {
        theme = 'moon';
        return theme;
    }

    if (theme === 'error') {
        theme = 'sun';
        return theme;
    }

    if (theme === 'warning') {
        theme = 'ocean';
        return theme;
    }

    return 'grassland';

}