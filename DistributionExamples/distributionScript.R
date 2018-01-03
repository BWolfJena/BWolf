setwd("C:\\Users\\Greifvogel\\Programming\\Git\\BWolf\\DistributionExamples")
library(RJSONIO)
# Number of courses
courses <- 16
# Number of Students
numberStudents <- 1500

# Functions
aggregationMatrix <- function(listOfLists) {
  A <- matrix(0, nrow = courses, ncol = courses)
  
  for (studentPref in listOfLists) {
    indexCourse <- 1
    for (pref in studentPref) {
      A[pref,indexCourse] <- A[pref,indexCourse] + 1
      indexCourse <- indexCourse + 1
    }
  }
  
  return(A)
}

#Probability functions
normalDistribution <- function(x, mu = (length(x)+1)/2, sigma = 3) {
  prefactor <- 1/sqrt(2*pi*(sigma^2))
  exponent <- - (x - mu)^2 / (2*sigma^2)
  return(prefactor * exp(exponent))
}
normalProbability <- normalDistribution(1:courses, sigma = 2)
uniformProbability <- rep(1/courses, courses)

# Courses are columns, Students are rows
uniformPreferences <- vector(mode="list", length=numberStudents)
normalPreferences <- vector(mode="list", length=numberStudents)
uni <- matrix(0, nrow = numberStudents, ncol = courses)
normal <- matrix(0, nrow = numberStudents, ncol = courses)

for (i in 1:numberStudents) {
  uniformPreferences[[i]] <- sample(1:courses, courses, prob = uniformProbability)
  normalPreferences[[i]] <- sample(1:courses, courses, prob = normalProbability) 
  uni[i, ] <- sample(1:courses, courses, prob = uniformProbability)
  normal[i, ] <- sample(1:courses, courses, prob = normalProbability) 
}

exportJson <- toJSON(uniformPreferences)
write(exportJson, "uniformPreferences.json")
exportJson <- toJSON(normalPreferences)
write(exportJson, "normalPreferences.json")


write.csv(aggregationMatrix(normalPreferences), file = "aggregatedNormPref.csv")
write.csv(aggregationMatrix(uniformPreferences), file = "aggregatedUniPref.csv")

write.csv(uni, file = "uniformPreferences.csv")
write.csv(normal, file = "normalPreferences.csv")