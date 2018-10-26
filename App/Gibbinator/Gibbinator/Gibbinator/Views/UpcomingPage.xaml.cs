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
	public partial class UpcomingPage : ContentPage
	{
        UpcomingViewModel viewModel;

        public UpcomingPage()
        {
            InitializeComponent();

            BindingContext = viewModel = new UpcomingViewModel();
        }

        async void OnItemSelected(object sender, SelectedItemChangedEventArgs args)
        {
            var lesson = args.SelectedItem as Lesson;
            if (lesson == null)
                return;

            await Navigation.PushAsync(new LessonDetailPage(new LessonDetailViewModel(lesson)));

            // Manually deselect item.
            UpcomingListView.SelectedItem = null;
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();

            if (viewModel.Lessons.Count == 0)
                viewModel.LoadLessonsCommand.Execute(null);
        }
    }
}