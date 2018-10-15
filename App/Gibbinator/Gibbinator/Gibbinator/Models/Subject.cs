using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    class Subject
    {
        public int SubjectId { get; set; }
        public string Name { get; set; }
        public Teacher HeadOfSubject { get; set; }
    }
}
