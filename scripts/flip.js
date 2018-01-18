function flipCard()
{
    var card = $('#main');
    var front = $('.flipcard-front');
    var back = $('.flipcard-back');
    var tallerHight = Math.max(front.height(), back.height()) + 'px';
    // visible/invisible *before* the card is flipped
    var visible = front.hasClass('ms-front-flipped') ? back : front;
    var invisible = front.hasClass('ms-front-flipped') ? front : back;
    var hasTransitioned = false;
    var onTransitionEnded = function () {
        hasTransitioned = true;
        card.css({
            'min-height': '0px'
        });
        visible.css({
            display: 'none',
        });
        // setting focus is important for keyboard users who might otherwise
        // interact with the back of the card once it is flipped.
        invisible.css({
            position: 'relative',
            display: 'inline-block',
        }).find('button:first-child,a:first-child').focus();
    }

    // this is bootstrap support, but you can listen to the browser-specific
    // events directly as well
    card.one($.support.transition.end, onTransitionEnded);

    // for browsers that do not support transitions, like IE9
    setTimeout(function () {
        if (!hasTransitioned) {
            onTransitionEnded.apply();
        }
    }, 2000);

    invisible.css({
        position: 'absolute',
        display: 'inline-block'
    });

    card.css('min-height', tallerHight);
    // the IE way: flip each face of the card
    front.toggleClass('ms-front-flipped');
    back.toggleClass('ms-back-flipped');
    // the webkit/FF way: flip the card
    card.toggleClass('card-flipped');
}