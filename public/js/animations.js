//detect animations vendor
var _animationEvent = animationVendor();

function animationVendor(){
  var t,
      el = document.createElement("fakeelement");

  var animations = {
    "animation"      : "animationend",
    "OAnimation"     : "oAnimationEnd",
    "MozAnimation"   : "animationend",
    "WebkitAnimation": "webkitAnimationEnd"
  };

  for (t in animations){
    if (el.style[t] !== undefined){
      return animations[t];
    }
  }
};

function animateShow(obj, animation) {
    obj.addClass("animated "+animation).show().one(_animationEvent, function () {
        $(this).removeClass("animated "+animation);
    });
};

function gritterShow(type, title, msg, time) {
    time = time || 4000;
    switch (type) {
        case 'success':
            icon = 'fa-check-circle'; break;
        case 'danger':
            icon = 'fa-times-circle'; break;
        case 'warning':
            icon = 'fa-exclamation-circle'; break;
        case 'info':
            icon = 'fa-info-circle'; break;
    }
    
    
    $.gritter.add({
        title: '<i class="fa '+icon+'"></i> '+title,
        text: msg,
        sticky: false,
        time: time,
        class_name: 'gritter-'+type
    });
}