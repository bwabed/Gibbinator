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
    public partial class MessageDetailPage : ContentPage
    {
        MessageDetailViewModel viewModel;

        public MessageDetailPage(MessageDetailViewModel viewModel)
        {
            InitializeComponent();

            BindingContext = this.viewModel = viewModel;
        }

        public MessageDetailPage()
        {
            InitializeComponent();

            var message = new Message
            {
                MessageTitle = "Item 1",
                MessageDescription = "This is a message description."
            };

            viewModel = new TeacherDetailViewModel(message);
            BindingContext = viewModel;
        }
    }
}