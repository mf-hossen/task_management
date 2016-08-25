--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `age` smallint(6) NOT NULL,
  `roll` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `name`, `age`, `roll`) VALUES
(1, 'emon', 28, 111),
(2, 'sumon', 25, 222),
(3, 'mamun', 12, 222),
(4, 'kader', 12, 11),
(5, 'hassan', 12, 12),
(6, 'sohid', 12, 22);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
