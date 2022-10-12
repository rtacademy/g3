"use strict";
$( document ).ready( function()
{
    const dataTable = $( "#table" ).DataTable(
        {
            searching: false,
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: '/api/user/list',
            columns: [
                { data: 'id' },
                { data: 'token' },
                { data: 'created_date', render: ( data ) => moment(data).format( 'DD.MM.YYYY HH:mm:ss' ) },
                { data: 'status', render: ( data ) => data.charAt(0).toUpperCase() + data.slice(1) },
            ],
            keys        : !0
        }
    );
} );