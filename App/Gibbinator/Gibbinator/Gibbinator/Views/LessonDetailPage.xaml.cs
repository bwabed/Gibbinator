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
	public partial class LessonDetailPage : ContentPage
	{
        LessonDetailViewModel viewModel;

        public LessonDetailPage(LessonDetailViewModel viewModel)
        {
            InitializeComponent();

            BindingContext = this.viewModel = viewModel;
        }

        public LessonDetailPage()
        {
            InitializeComponent();

            var lesson = new Lesson
            {
               Description="",
               Course="",
            };

            viewModel = new LessonDetailViewModel(lesson);
            BindingContext = viewModel;
        }
    }
}