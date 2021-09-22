
function requestData(url) {
    var oReq = new XMLHttpRequest();
    oReq.addEventListener("load", transferComplete, false);
    oReq.addEventListener("error", transferFailed, false);
    oReq.open("GET", url, true);
    oReq.send();

    function transferComplete(evt) {
        var vteste = evt.currentTarget.responseText;

        var vjson = JSON.parse(vteste);
        generateLogTemplate(vjson, url);

    }
    function transferFailed(evt) {
        alert("Um erro ocorreu durante a transferência do arquivo.");
    }
}
function generateLogTemplate(pjsonlist, purlBase){
    if(pjsonlist.length > 0) {
        const result = `
        <ul>
            ${pjsonlist.map(
            json =>
                `<li> GET ${json.url_link} </li>
                    <li> ${json.responseCode} </li>
                    <li><a href="${purlBase}/${json.log_id}"> click here to see the requested body</a></li>`
        )}
        </ul>`;
        document.body.getElementsByClassName('history_logs')[0].innerHTML = result;
    }else{
        document.body.getElementsByClassName('history_logs')[0].innerHTML = 'Nenhum resultado encontrado!';
    }

}