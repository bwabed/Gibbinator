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
	public partial class TeacherPage : ContentPage
	{
        TeachersViewModel viewModel;

        public TeacherPage()
        {
            InitializeComponent();

            BindingContext = viewModel = new TeachersViewModel();
        }

        async void OnItemSelected(object sender, SelectedItemChangedEventArgs args)
        {
            var teacher = args.SelectedItem as Teacher;
            if (teacher == null)
                return;

            await Navigation.PushAsync(new TeacherDetailPage(new TeacherDetailViewModel(teacher)));

            // Manually deselect item.
            TeachersListView.SelectedItem = null;
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();

            if (viewModel.Teachers.Count == 0)
                viewModel.LoadTeachersCommand.Execute(null);
        }

    }
}