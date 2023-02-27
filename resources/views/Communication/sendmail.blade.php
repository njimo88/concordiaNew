<!-- /*! 
 *  Multiple select dropdown with filter jQuery plugin.
 *  Copyright (C) 2022  Andrew Wagner  github.com/andreww1011
 *
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 * 
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 * 
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301
 *  USA
 */ -->
 <!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Filter Multi Select Plugin</title>
    <style>
      .notification {color: red; font-size: 85%;}
    </style>
  </head>

  <body>
    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!-- Load Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Load the plugin bundle. -->
    <link rel="stylesheet" href="../filter-multi/dist/filter_multi_select.css" />
    <script src="../filter-multi/dist/filter-multi-select-bundle.min.js"></script>

    <section class="alternate page-heading">
      <div class="container">
        <header>
          <h1>Envoi de mail</h1>
        </header>
        <p class="lead"></p>
    </section>
    <hr>
    <div class="container">
     
    </div>
    <form class="container" method="GET" id="form">
     
      <div class="row">
        <h4 class="col-12">Dropdown with pre-sets and case-sensitive filtering</h4>
      </div>
      <div class="form-group row">
        <label class="col-2 col-form-label" for="shapes"></label>
        <div class="col-10">
       
          <select multiple name="shapes" id="shapes">
          @foreach($user as $data)
            <option value="2">circle</option>
            @endforeach
            
            <option value="3">polygon</option>
            <option value="4">Ellipse</option>
            <option value="5">Triangle</option>
           
          </select>
        </div>
      </div>
    
      <button type="submit">Submit Form</button>
    </form>

    <script>
      // Use the plugin once the DOM has been loaded.
      $(function () {
        // Apply the plugin 
        var notifications = $('#notifications');
        $('#animals').on("optionselected", function(e) {
          createNotification("selected", e.detail.label);
        });
        $('#animals').on("optiondeselected", function(e) {
          createNotification("deselected", e.detail.label);
        });
        function createNotification(event,label) {
          var n = $(document.createElement('span'))
            .text(event + ' ' + label + "  ")
            .addClass('notification')
            .appendTo(notifications)
            .fadeOut(3000, function() {
              n.remove();
            });
        }
        var shapes = $('#shapes').filterMultiSelect({
          selectAllText: 'all...',
          placeholderText: 'click to select a shape',
          filterText: 'search',
          labelText: 'Shapes',
          caseSensitive: true,
        });
        var cars = $('#cars').filterMultiSelect();
        var pl1 = $('#programming_languages_1').filterMultiSelect();
        $('#b1').click((e) => {
          pl1.enableOption("1");
        });
        $('#b2').click((e) => {
          pl1.disableOption("1");
        });
        var pl2 = $('#programming_languages_2').filterMultiSelect();
        $('#b3').click((e) => {
          pl2.enable();
        });
        $('#b4').click((e) => {
          pl2.disable();
        });
        var pl3 = $('#programming_languages_3').filterMultiSelect({
          allowEnablingAndDisabling: false,
        });
        $('#b5').click((e) => {
		  pl3.enableOption("1");
        });
        $('#b6').click((e) => {
          pl3.disableOption("1");
        });
        $('#b7').click((e) => {
          pl3.enable();
        });
        $('#b8').click((e) => {
          pl3.disable();
        });
        var cities = $('#cities').filterMultiSelect({
          items: [["San Francisco","a"],
                  ["Milan","b",false,true],
                  ["Singapore","c",true],
                  ["Berlin","d",true,true],
                 ],
        });
        var colors = $('#colors').filterMultiSelect();
        var trees = $('#trees').filterMultiSelect({
          selectionLimit: 3,
        });
        $('#jsonbtn1').click((e) => {
           var b = true;
           $('#jsonresult1').text(JSON.stringify(getJson(b),null,"  "));
         });
         var getJson = function (b) {
           var result = $.fn.filterMultiSelect.applied
               .map((e) => JSON.parse(e.getSelectedOptionsAsJson(b)))
               .reduce((prev,curr) => {
                 prev = {
                   ...prev,
                   ...curr,
                 };
                 return prev;
               });
           return result;
         }
         $('#jsonbtn2').click((e) => {
           var b = false;
           $('#jsonresult2').text(JSON.stringify(getJson(b),null,"  "));
         });
        $('#form').on('keypress keyup', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) { 
            e.preventDefault();
            return false;
          }
        });
      });
    </script>
  </body>

</html>

