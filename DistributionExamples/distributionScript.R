######
# Set working directory + Libraries
######
#setwd("C:\\Users\\Greifvogel\\Programming\\Git\\BWolf\\DistributionExamples")
library(RJSONIO)

######
# Constants
######

# Number of courses
courses <- 30
# Number of Students
numberStudents <- 1500

######
# Helper
######

# Aggregates all values of the provided list of lists
aggregationMatrix <- function(listOfLists) {
  # Initialize matrix
  aggregation <- matrix(0, nrow = courses, ncol = courses)
  # For every student's preference list
  for (studentPref in listOfLists) {
    # Get the index of the preference
    indexCourse <- 1
    # For every preference in a student's preference list
    for (pref in studentPref) {
      # Increment the preference at the course by one
      aggregation[pref,indexCourse] <- aggregation[pref,indexCourse] + 1
      # Increment the course index
      indexCourse <- indexCourse + 1
    }
  }
  # Return the matrix
  return(aggregation)
}

######
# Density functions
######

# Calculates the normal distribution for x points
normalDistribution <- function(x, mu = (length(x)+1)/2, sigma = 1) {
  prefactor <- 1/sqrt(2*pi*(sigma^2))
  exponent <- - (x - mu)^2 / (2*sigma^2)
  return(prefactor * exp(exponent))
}

# Calculates a multivariat distribution for n normal distributions 
multivariatDistribution <- function(n, mu = rep(0, n), sigma = rep(1, n)) {
  # Create a list to save all distributions
  distributions <- rep(0, courses)
  # Calculate n normal distributions and divide each by n
  for (i in 1:n) {
    distributions <- distributions + normalDistribution(1:courses, mu[i], sigma[i])/n
  }
  # Return the sum of the distributions
  return(distributions)
}

######
# Probabilities
######

normalProbability <- normalDistribution(1:courses, sigma = 2)
uniformProbability <- rep(1/courses, courses)
multivariatProbability <- multivariatDistribution(3, mu = c(5, 15, 25), sigma = c(1, 2, 1))

######
# Sampling
######

# Courses are columns, Students are rows
uniformPreferences <- vector(mode="list", length=numberStudents)
normalPreferences <- vector(mode="list", length=numberStudents)
multivariatPreferences <- vector(mode="list", length=numberStudents)
uni <- matrix(0, nrow = numberStudents, ncol = courses)
normal <- matrix(0, nrow = numberStudents, ncol = courses)
multi <- matrix(0, nrow = numberStudents, ncol = courses)

# Sample for each student
for (i in 1:numberStudents) {
  uni[i, ] <- sample(1:courses, courses, prob = uniformProbability)
  normal[i, ] <- sample(1:courses, courses, prob = normalProbability)
  multi[i, ] <- sample(1:courses, courses, prob = multivariatProbability)
}
uniformPreferences <- as.list(data.frame(t(uni)))
normalPreferences <- as.list(data.frame(t(normal)))
multivariatPreferences <- as.list(data.frame(t(multi)))

######
# JSON/CSV Export
######

# Export distritbution to JSON
exportJson <- toJSON(uniformPreferences)
write(exportJson, "uniformPreferences.json")
exportJson <- toJSON(normalPreferences)
write(exportJson, "normalPreferences.json")
exportJson <- toJSON(multivariatPreferences)
write(exportJson, "multivariatPreferences.json")
# Export aggregated to CSV
write.csv(aggregationMatrix(uniformPreferences), file = "aggregatedUniPref.csv")
write.csv(aggregationMatrix(normalPreferences), file = "aggregatedNormPref.csv")
write.csv(aggregationMatrix(multivariatPreferences), file = "aggregatedMultiPref.csv")
# Export distritbution to Csv
write.csv(uni, file = "uniformPreferences.csv")
write.csv(normal, file = "normalPreferences.csv")
write.csv(multi, file = "multiPreferences.csv")

######
# Plotting
######

barplot(t(aggregationMatrix(uniformPreferences)))
barplot(t(aggregationMatrix(normalPreferences)))
barplot(t(aggregationMatrix(multivariatPreferences)))
