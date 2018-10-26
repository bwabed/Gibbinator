using Gibbinator.Models;
using Gibbinator.ViewModels;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Gibbinator.Views
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class MessagesPage : ContentPage
	{
        MessagesViewModel viewModel;

        public MessagesPage ()
		{
            InitializeComponent();

            BindingContext = viewModel = new MessagesViewModel();
        }

        async void OnItemSelected(object sender, SelectedItemChangedEventArgs args)
        {
            var message = args.SelectedItem as Message;
            if (message == null)
                return;

            await Navigation.PushAsync(new TeacherDetailPage(new MessageDetailViewModel(message)));

            // Manually deselect item.
            MessagesListView.SelectedItem = null;
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();

            if (viewModel.Messages.Count == 0)
                viewModel.LoadMessagesCommand.Execute(null);
        }

    }
}