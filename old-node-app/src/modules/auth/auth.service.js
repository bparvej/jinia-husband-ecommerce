const bcrypt = require('bcryptjs');
const authRepository = require('./auth.repository');
const logger = require('../../utils/logger');

class AuthService {
  async login(email, password) {
    const user = await authRepository.findUserByEmail(email);
    if (!user) {
      throw new Error('Invalid email or password');
    }

    const isValidPassword = await bcrypt.compare(password, user.password);
    if (!isValidPassword) {
      throw new Error('Invalid email or password');
    }

    await authRepository.updateLastLogin(user.id);
    logger.info(`User logged in: ${user.email}`, { userId: user.id, role: user.Role.name });

    return user;
  }

  async register(data) {
    const existing = await authRepository.findUserByEmail(data.email);
    if (existing) {
      throw new Error('Email already registered');
    }

    const hashedPassword = await bcrypt.hash(data.password, 12);
    const user = await authRepository.createUser({
      ...data,
      password: hashedPassword,
      role_id: 4, // customer role
    });

    logger.info(`New user registered: ${data.email}`, { userId: user.id });
    return user;
  }

  async getUserById(id) {
    return authRepository.findUserById(id);
  }
}

module.exports = new AuthService();
