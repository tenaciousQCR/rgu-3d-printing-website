function loadMap() {
    let map = new Microsoft.Maps.Map(document.getElementById('map'), {});

    map.setView({
        center: new Microsoft.Maps.Location(51.4621789, -0.1141351),
        zoom: 18
    });

    let center = map.getCenter();
    // Show loc with pin
    var pin = new Microsoft.Maps.Pushpin(center, {
        title: '3D Print Co',
        subTitle: 'Headquarters',
        text: ''
    });

    //Add the pushpin to the map
    map.entities.push(pin);
}