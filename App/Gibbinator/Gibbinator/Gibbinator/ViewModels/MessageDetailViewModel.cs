using Gibbinator.Models;
using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.ViewModels
{
    public class MessageDetailViewModel : BaseViewModel
    {
        public Message Message { get; set; }
        public MessageDetailViewModel(Message message = null)
        {
            Title = message?.MessageTitle;
            Message = message;
        }
    }
}
