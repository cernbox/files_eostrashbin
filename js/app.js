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
				detailsViewEnabled: false,
				scrollTo: urlParams.scrollto,
				config: OCA.Files.App.getFilesConfig()
			}
		);
	},

	_createFileActions: function() {
		var fileActions = new OCA.Files.FileActions();
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

		return fileActions;
	}
};

$(document).ready(function() {
	$('#app-content-eostrashbin').one('show', function() {
		var App = OCA.EosTrashbin.App;
		App.initialize($('#app-content-eostrashbin'));
		// force breadcrumb init
		App.fileList.changeDirectory(App.fileList.getCurrentDirectory(), false, true);
	});
});

