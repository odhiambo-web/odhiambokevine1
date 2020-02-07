<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.5.1/leaflet.css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.5.1/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
<script>
    let mapOptions = {
        center: [0.4371,36.9580],
        zoom: 7
    };
    let accidents = new L.map('accidents', mapOptions);

    let basemaps = {
        'OSM' : L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),

        'Google Streets' : L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        })

    };

    let  thematicLayers = {
        'Hospitals' : L.layerGroup()
    };

    L.control.layers(basemaps, thematicLayers).addTo(accidents);

    function initMap() {
        basemaps.OSM.addTo(accidents);
        let keys = Object.keys(thematicLayers);
        keys.forEach(key => {
            thematicLayers[key].addTo(accidents)
        });
    }

    function pan(x,y){
        accidents.setView([x,y], 9);
    }

    function fitBounds(corner1, corner2){
        accidents.fitBounds([corner1, corner2]);
    }
    function getHospitals() {
        axios.post('hospitals.php', data)
            .then(res => {
                if (res.data.success && res.data.hospitals){
                    thematicLayers['Hospitals'].clearLayers();
                    res.data.hospitals.forEach(x => {
                        thematicLayers['Hospitals'].addLayer(L.circle([x.long, x.lat], {
                            color: "blue",
                            fillColor: "#f03",
                            fillOpacity: 0.5,
                            radius: 2.0
                        }));
                    })
                }
            })
            .catch(err => {
                console.log(err)
            })
    }
    initMap();
    getHospitals();
</script>