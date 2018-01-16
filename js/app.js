/**
 * @namespace OCA.EosTrashbin
 */
OCA.EosTrashbin = {};
/**
 * @namespace OCA.EosTrashbin.App
 */
OCA.EosTrashbin.App = {
	_initialized: false,

	initialize: function($el) {
		if (this._initialized) {
			return;
		}
		this._initialized = true;
		var urlParams = OC.Util.History.parseUrlQuery();
		this.fileList = new OCA.EosTrashbin.FileList(
			$('#app-content-eostrashbin'), {
				scrollContainer: $('#app-content'),
				fileActions: this._createFileActions(),
				detailsViewEnabled: true,
				scrollTo: urlParams.scrollto,
				//config: OCA.Files.App.getFilesConfig()
			}
		);
	},

	_createFileActions: function() {
		var fileActions = new OCA.Files.FileActions();
		fileActions.register('dir', 'Open', OC.PERMISSION_READ, '', function (filename, context) {
			var dir = context.fileList.getCurrentDirectory();
			context.fileList.changeDirectory(OC.joinPaths(dir, filename));
		});

		fileActions.setDefault('dir', 'Open');

		fileActions.registerAction({
			name: 'Restore',
			displayName: t('files_eostrashbin', 'Restore'),
			type: OCA.Files.FileActions.TYPE_INLINE,
			mime: 'all',
			permissions: OC.PERMISSION_READ,
			iconClass: 'icon-history',
			actionHandler: function(filename, context) {
				var fileList = context.fileList;
				var tr = fileList.findFileEl(filename);
				var deleteAction = tr.children("td.date").children(".action.delete");
				deleteAction.removeClass('icon-delete').addClass('icon-loading-small');
				fileList.disableActions();
				$.post(OC.filePath('files_eostrashbin', 'ajax', 'undelete.php'), {
						files: JSON.stringify([filename]),
						dir: fileList.getCurrentDirectory()
					},
					_.bind(fileList._removeCallback, fileList)
				);
			}
		});

		/* EOS does not support single file purging so we disable it
		fileActions.registerAction({
			name: 'Delete',
			displayName: t('files', 'Delete'),
			mime: 'all',
			permissions: OC.PERMISSION_READ,
			iconClass: 'icon-delete',
			render: function(actionSpec, isDefault, context) {
				var $actionLink = fileActions._makeActionLink(actionSpec, context);
				$actionLink.attr('original-title', t('files_eostrashbin', 'Delete permanently'));
				$actionLink.children('img').attr('alt', t('files_eostrashbin', 'Delete permanently'));
				context.$file.find('td:last').append($actionLink);
				return $actionLink;
			},
			actionHandler: function(filename, context) {
				var fileList = context.fileList;
				$('.tipsy').remove();
				var tr = fileList.findFileEl(filename);
				var deleteAction = tr.children("td.date").children(".action.delete");
				deleteAction.removeClass('icon-delete').addClass('icon-loading-small');
				fileList.disableActions();
				$.post(OC.filePath('files_eostrashbin', 'ajax', 'delete.php'), {
						files: JSON.stringify([filename]),
						dir: fileList.getCurrentDirectory()
					},
					_.bind(fileList._removeCallback, fileList)
				);
			}
		});
		*/

		return fileActions;
	}
};

$(document).ready(function() {
	$('#app-content-eostrashbin').one('show', function() {
		var App = OCA.EosTrashbin.App;
		App.initialize($('#app-content-eostrashbin'));
		// force breadcrumb init
		App.fileList.changeDirectory(App.fileList.getCurrentDirectory(), false, true);
		// hide purge action
		$("#app-content-eostrashbin").find(".delete-selected").remove();
	});
});

