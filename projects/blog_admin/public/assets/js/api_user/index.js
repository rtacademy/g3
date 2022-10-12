"use strict";

const renderActionsCell = ( data, type, row, meta ) =>
`
    <a href="/api/user/view/${row.id}" class="btn" title="View"><i class="uil-eye"></i></a>
    <a href="/api/user/edit/${row.id}" class="btn" title="Edit"><i class="uil-pen"></i></a>
    <a href="#" data-url="/api/user/delete/${row.id}" class="btn action-delete" title="Delete"><i class="uil-trash"></i></a>
`;

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
                { render: renderActionsCell },
            ],
            keys        : !0
        }
    );

    $('#table').on(
        'click',
        'a.action-delete',
        function( e )
        {
            e.preventDefault();
            $.ajax(
                {
                    'method'    : 'DELETE',
                    'url'       : $(this).data('url'),
                }
            );
        }
    );
} );