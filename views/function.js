/* 
 * take winnigs table and allow user to download as .xls
 * */

function exportTableToExcel(tableID, filename = 'winnigs'){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    //empty rows serve no purpose. why are they even there?
    var tableHTML = tableHTML.replace(/<tr><\/tr>/g,''); 
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
/* send updated table back to welcome.php*/
function showTable() {
     
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            //if (this.readyState == 4 && this.status == 200) {
                document.getElementById("table_row").innerHTML = this.responseText;
            //}
        };
        xmlhttp.open("GET","data_request.php",true);
        xmlhttp.send();
    
}


