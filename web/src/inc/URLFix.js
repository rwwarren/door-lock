var URLFix = {
    fix: function() {
        var hash = document.location.hash;
        if (!hash) {
            return;
        }

        if (hash.length < 4 || hash[2] !== '/') {
            return;
        }

        var url = hash.substr(3);
        history.replaceState('', '', window.location.origin + '/' + url);
    },
};

module.exports = URLFix;